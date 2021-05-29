<?php
/**
 *  Author: Pavel Belyaev
 *  GitHub: https://github.com/TechResearchRu/LE_DRAFT
 *  Email: pavelbbb@gmail.com
 *  LE FRAMEWORK, LE_CURL v0.1 2021, need CURL and ICONV modules in PHP
 */

class LE_CURL
{
	public $timeout=40; //sec
	public $last_url=""; //auto referrer
	public $last_code="";
	public $cook_file=false;
	public $debug=0;
	public $user_agent="Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/24.0";
	protected $CL=false;
	public $encode=0;
	public $redirect=false;
	
	
	public function url_prepare($url)
	{
		return str_replace(' ','%20',$url);
	}
	
	protected function options($cust=[])
	{
		curl_setopt($this->CL,CURLOPT_RETURNTRANSFER,true);
		//curl_setopt($this->CL,CURLOPT_BINARYTRANSFER,false);
		curl_setopt($this->CL,CURLOPT_VERBOSE, $this->debug);
		curl_setopt($this->CL,CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($this->CL,CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($this->CL, CURLOPT_SSL_VERIFYPEER, false); //curl.cainfo=/path/to/downloaded/cacert.pem
		curl_setopt($this->CL,CURLOPT_FOLLOWLOCATION, true); //переходить по редиректам
        curl_setopt($this->CL, CURLOPT_ENCODING, "gzip, deflate");
	}

	protected function cook()
	{
		if (($this->cook_file!==0 && !empty($this->cook_file))===false) return;
		curl_setopt($this->CL, CURLOPT_COOKIEJAR, $this->cook_file);
		curl_setopt($this->CL, CURLOPT_COOKIEFILE, $this->cook_file);
	}
	
	//инициализация....
	protected function start($inp)
    {
		$this->redirect=0;
		$this->CL = curl_init($this->url_prepare($inp['url']));
		$this->options();

		if (isset($inp['get_redirect']) && $inp['get_redirect']) 
            curl_setopt($this->CL,CURLOPT_FOLLOWLOCATION, false);

		if (isset($inp['headers']) && is_array($inp['headers'])) 
            curl_setopt($this->CL, CURLOPT_HTTPHEADER, $inp['headers']);
		
        $this->cook();
		
		if (isset($inp['post']) && !empty($inp['post']))
		{
			curl_setopt($this->CL, CURLOPT_POST, 1);
			
            if ($inp['post']!==1) 
                curl_setopt($this->CL, CURLOPT_POSTFIELDS, $inp['post']);	
		}

        if (isset($inp['method']) && !empty($inp['method'])) curl_setopt($this->CL, CURLOPT_CUSTOMREQUEST, $inp['method']);
		if(isset($inp['raw'])) curl_setopt($this->CL, CURLOPT_POSTFIELDS, $inp['raw']);
		if (isset($inp['ref']) && !empty($inp['ref'])) curl_setopt($this->CL, CURLOPT_REFERER, $inp['ref']);
    }
	
	//сохранить в файл
	public function to_file($inp,$path,$f_name=false)
    {
		if (is_string($inp)) $inp = ['url'=>$inp];
		$this->start($inp);
		$path = rtrim($path,'/\\').DIRECTORY_SEPARATOR.(($f_name) ? $f_name : basename($inp['url']));
		$fp = fopen($path, "w");
		curl_setopt($this->CL, CURLOPT_FILE,$fp);
		curl_exec($this->CL);
		curl_close($this->CL);
		fclose($fp);
		return $path;
    }

    public function ex($inp)
    {
        $content = curl_exec($this->CL);
        $this->last_code = curl_getinfo($this->CL, CURLINFO_HTTP_CODE);
        $type = curl_getinfo($this->CL, CURLINFO_CONTENT_TYPE);

        if ($this->encode!==0) 
			return iconv($this->encode,"UTF-8", $content);
        else
        {
            $_type = $this->reg_find($type,"([a-z\/]+[\s;]+)charset=([^;]+)?");
            if (!empty($_type[2]) && $_type[2]!='utf-8') return iconv($_type[2],"UTF-8", $content);
        }

        return $content;

    }
	

	public function query($inp)
	{
		if (is_string($inp)) $inp = ['url'=>$inp];
		$this->start($inp);
		$content = $this->ex($inp);
		
        if (isset($inp['get_redirect']) && $inp['get_redirect']) 
            $this->redirect = curl_getinfo($this->CL,CURLINFO_REDIRECT_URL);

		curl_close($this->CL);
		
		return $content;
	}

	public function reg_find($cont,$find,$key=false)
	{
		if (empty($cont)) return false;
		if(!preg_match('|'.$find.'|simu',$cont,$res)) return false;
		return ($key!==false && isset($res[$key])) ? $res[$key] : $res;
	}
}