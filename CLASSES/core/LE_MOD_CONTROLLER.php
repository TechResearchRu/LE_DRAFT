<?php
abstract class LE_MOD_CONTROLLER 
{
    public $aliases, $params_url, $model,$cont_type;
    
    
    function __construct($params_url=false,&$model=false)
    {
        $this->params_url = $params_url;
        $this->model = &$model;
    }

    //точка входа
	public function start()
	{
		//ajax methods
        if (isset($_POST['ajax'])) return $this->ajax();

        if (LE::$QUERY_DATA_TYPE=='json') return $this->json();
        
		
		list($mod,$params) =  $this->router();

		if (!empty($mod) && isset($this->aliases[$mod])) $mod = $this->aliases[$mod];		
		
        $mod = "_inp_".$mod;



        if ($mod=="_inp_" || !method_exists($this,$mod)) return $this->_inp_default($params);

		return $this->$mod($params); 
	}

	protected function router()
	{
		//echo_arr($this->params_url);
        $url = $this->params_url[3];
        $url = PRE::DOWN($url);
        preg_match('!([^:]*)[:]?(.*)!ui',$url,$out);

        //echo_arr($out);
		return [$out[1],$out[2]];
	}

	protected function ajax()
	{
        //out without template
        if (property_exists(LE::$TPL,'clear')) LE::$TPL->clear=1;

		$mod = trim(arr_v($_POST,'mod',''));
        if (empty($mod)) return false;

        $mod = "_ajx_".$mod;

        $data=false;
        if (isset($_POST['data']))
        {
            $data = $_POST['data'];
            if (!is_array($data) && !empty($data)) $data = json_decode($data,1);
        }

		if (!method_exists($this,$mod)) return false;
		
		$res = $this->$mod($data);


        //возвращать массив ответа как есть, не оборачивая в data
        if (isset($res['as_is']) && $res['as_is'])
        {
            unset($res['as_id']);
            $out = $res;
        }
        
        elseif ($res===false)
        {
            $out = ['success'=>0]; //error
        } 
        else
        {
            $out = ['success'=>1];
            $out['data'] = $res;
        }

        return json_encode($out);
		

	}


	protected function _inp_default($inp)
	{
		return false;
	}

    protected function json()
    {
        $postData = file_get_contents('php://input');
        $data = json_decode($postData,1);

        if (property_exists(LE::$TPL,'clear')) LE::$TPL->clear=1;

		
        $method = trim(arr_v($data,'method',''));
        if (empty($method)) return false;

        $method = "_ajx_".$method;

        

		if (!method_exists($this,$method)) return false;
		
		unset($data['method']);
        $res = $this->$method($data);


        //возвращать массив ответа как есть, не оборачивая в data
        if (isset($res['as_is']) && $res['as_is'])
        {
            unset($res['as_is']);
            $out = $res;
        }
        
        elseif ($res===false)
        {
            $out = ['success'=>0]; //error
        } 
        else
        {
            $out = ['success'=>1];
            $out['data'] = $res;
        }

        return json_encode($out);
    }

	//301 redirect
	public function move2new($url)
	{
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$url);
        exit();
	}

}