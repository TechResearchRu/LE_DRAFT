<?

/**
 * 
 */
class MP_VK 
{
	public $web,$api_v,$client_id,$secure_key,$service_key,$token;
	public $group_key = "";
	public $group_id = "";
	public $admin_id = "";


	function __construct($i=[])
	{
		if (isset($i['web'])) $this->web = &$i['web'];
		$this->api_v = arr_v($i,'api_v','5.92');
		$this->client_id = arr_v($i,'client_id','0');
		$this->secure_key = arr_v($i,'secure_key','0');
		$this->service_key = arr_v($i,'service_key','0');
	}

	public function getf_act($cont)
	{
		return $this->web->reg_find($cont,'<[^>]+action="([^"]+)"',1);

	}

	public function url2token($url)
	{
		$url = str_replace('https://oauth.vk.com/blank.html#', '', $url);
		parse_str($url, $out);
		if (!isset($out['access_token'])) return false;
		return $out['access_token'];
	}


	
	public function web_auth($login,$pass)
	{
		$ref = $inp['url'] = "https://m.vk.com";
		$html = $this->web->get_cont($inp);

		$inp['url'] = $this->getf_act($html);
		$inp['ref'] = $ref;
		$inp['post'] = ['email'=>$login,'pass'=>$pass];
		return true;
	}

	//тут еще надо доработать на примере ботинка...
	public function oauth()
	{
		$url = 'https://oauth.vk.com/authorize?client_id='.$this->client_id;
		$url.='&display=mobile&redirect_uri=https://oauth.vk.com/blank.html';
		//https://vk.com/dev/permissions
		$url.='&scope='.(2+4+8+16+64+256+1024+8192+65536+262144+524288+1048576);
		$url.='&response_type=token&v='.$this->api_v;
		$inp['url'] = $url;
		
		//return;
		$inp['get_redirect'] =1;
		$html = $this->web->get_cont($inp);

		echo $html.BR.BR;

		//если уже есть доступ
		if ($this->web->redirect) 
		{
			$inp['url'] = $this->web->redirect;
			$html = $this->web->get_cont($inp);
			return $this->url2token($this->web->redirect);

		}

		//этот момент еще недоработан///
			 
		

		$inp['url'] = $html; 
		$html = $this->web->get_cont($inp);
		var_dump($html); echo BR.BR; return;


		$inp['post'] = 1;
		//echo $html.BR.BR;

		$inp['url'] = $this->getf_act($html);
		return;
		echo $inp['url'].BR;

		$inp['ref'] = $url;
		
		

		$res = $this->web->get_cont($inp);

		var_dump($res);

		return true;
	}




	public function query($method,$data=[],$access_token=1)
	{
		$inp['url'] = "https://api.vk.com/method/".$method;
		if ($access_token) $data['access_token'] = $this->token;
		$data['v'] = $this->api_v;
		$inp['post'] = $data;

		$res = $this->web->get_cont($inp);
		$res = json_decode($res,1);
		if(isset($res['error']))
		{
			print_r($res['error']);
			return false;
		}

		print_r($res);

		return $res['response'];



	}

	public function long_pool_init()
	{
		$par = ['group_id'=>$this->group_id,'access_token'=>$this->group_key];
		$c = $this->query("groups.getLongPollServer",$par);
		$url = $c['server']."?act=a_check&key=".$c['key'].'&wait=25';
		return [$c,$url];
	}


	public function long_pool(&$f=false,$timeout=0)
	{

		if ($f===false) $f = function($i){return false;};
		list ($c,$url) = $this->long_pool_init();
		

		while ($r=$this->web->get_cont($url.'&ts='.$c['ts'])) 
		{
			set_time_limit(0);
			$_r  = $r; //debug
			$r = json_decode($r,1);

			if (isset($r['failed']))
			{
				switch ($r['failed']) 
				{
					case '1':
						$c['ts'] = $r['ts'];
						break;
					case '2':
					case '3':
						list ($c,$url) = $this->long_pool_init();
						echo BR.BR."UPDATE KEY".BR.BR;
						break;
				}
				continue;
			}
			if (!isset($r['ts'])) 
			{				
				echo "response hzhz".BR;
				var_dump($_r);
				echo "try again...".BR.BR;
				sleep(3);
				continue;
			}
			$c['ts'] = $r['ts'];



			if (is_array($r['updates']) && count($r['updates']))
				$f($r['updates']);
			
			

		}

		echo "ERROR GET URL, timeout: ".$timeout.BR;
		sleep($timeout);
		$timeout+=1;
		$timeout*=2;

		return $this->long_pool($f,$timeout);
	}
	
	public function bot_message($user_id,$message)
	{
		$data=[];
		if (is_string($message))
			$data['message'] = $message;
		elseif (is_array($message))
			$data = $message;

		$data['random_id'] = rand(0,99999);
		$data['peer_id'] = $this->group_id;
		$data['access_token']=$this->group_key;
		$data['user_id'] = $user_id;


		$this->query("messages.send",$data,0);
	}




}