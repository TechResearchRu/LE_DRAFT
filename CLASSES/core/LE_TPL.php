<?php

class LE_TPL {
    public $load_tpls=[]; //список загруженных шаблонов
    public $meta,$cont_top,$cont_bottom,$mod_cont,$head_cont;
    public $vars=[],$prefix,$tpl_arr,$debug=0,$clear=0;
            
    

    function __construct() 
    {
       $this->meta = ['title'=>'','keywords'=>'','description'=>''];
       $this->prefix="main";
       $this->mod_cont="";
       $this->vars = ['tpl_arr'=>&$this->tpl_arr,'tpl'=>&$this];
   	}

    
    public function fetch($t,&$vars=array(),$prefix=false,$cache_en=false)
    {   
        //выгружаем переменные в функцию
        if (is_array($this->vars)) extract($this->vars); 
        if (is_array($vars)) extract($vars);

        //определяем путь до шаблона
        $this->load_tpls[] = $__p  = $this->path($t,$prefix);
            
        //инклудим шаблон, буферизуем его выхлоп
        ob_start();
        include($__p);
        return ob_get_clean();
    }

    public function path($tpl_path,$prefix=false) 
	{		
		if ($prefix===false) $prefix = $this->prefix;
        $path = $prefix.DS.$tpl_path.".tpl";

        $path_app = realpath((APPDIR."TPL".DS.$path));
        $path_sys = realpath((SYSDIR."TPL".DS.$path));

        //echo APPDIR.$path.BR;
        //echo SYSDIR.$path.BR;

        if (is_file($path_app)) return $path_app;
        if (is_file($path_sys)) return $path_sys;

        exit($path." - NOT FOUND TPL");
	}

    public function display($prefix=false,$main_tpl="main")
	{
		//global $config,$db;
        
        $tpl = &$this;

		if ($this->debug) echo_arr($this->load_tpls);
        if (arr_v($_POST,'clear')=='yes') $this->clear=1;

        if($prefix===false) $prefix = $this->prefix;

        include SYSDIR."TPL".DS.$prefix.DS."static_list.php";
        $this->static_dep_apply();
        $this->add_need_static();
	
		
        $path= $this->path($main_tpl,$prefix);

		if ($this->clear) 
		{
			echo $this->mod_cont;
			return ($this->clear=0);
		}

		$tpl_arr = &$this->tpl_arr;		
		include($path);	
		
	}

    //static elements
    public $need_st_list=[],$static_list=[],$static_dep=[],$top_st=[],$bottom_st=[];

    public function need_static($inp)
    {
        if (!is_array($inp)) $inp = [$inp];
        $cnt = count($inp);

        for($i=0;$i<$cnt;$i++)
        {
            $v = $inp[$i];
            if (empty($v)) continue;
            $this->need_st_list[$v]=1;
        }
    }

    public function static_dep_apply($dep_list=false)
    {
        if ($dep_list===false) $dep_list=$this->static_dep;
        
        if (!is_array($dep_list) || empty($dep_list)) return;
        
        $need = &$this->need_st_list;

        foreach ($dep_list as $m_name => $dep_items) 
        {
            //для выключенных модулей не применяем зависимости
            if ( !(isset($need[$m_name]) && $need[$m_name]==1) ) continue;

            foreach ($dep_items as $k => $dep) 
            {
                $need[$dep]=1;
            }
        }

    }

    public function add_need_static()
    {
        $need = $this->need_st_list;
        $list = $this->static_list;

        foreach ($this->static_list as $key => $item) 
        {
           $pos = arr_v($item,'pos',false);
           $type = arr_v($item,'type',"");
           $link = arr_v($item,'link',"");
           //echo $link.BR;
           if ($pos===false) $pos = ($type=="js") ? "bottom" : "top";
           $mod=arr_v($item,'mod','default');

           if (  !((isset($need[$mod]) && $need[$mod]==1) || $mod=='default')  ) continue;
           
            switch ($type) 
            {
                case 'js':
                    $cont = $this->js($link);
                    break;
                case 'css':
                    $cont = $this->css($link);
                    break;
                
                default:
                    continue 2;
                    break;
            }

                if ($pos=="top")
                    $this->top_st[]=$cont;
                else
                    $this->bottom_st[]=$cont;

        }

        $_gl = "\n\t";
        $this->head_cont.=implode($_gl,$this->top_st);
        $this->cont_bottom.=implode($_gl,$this->bottom_st);
        
        //echo_arr($this->top_st);


    }

    public function css($p,$min=0)
	{      
		return '<link rel="stylesheet"  type="text/css" href="'.$this->p2min($p,$min,'css').'?v='.VER.'" />';
	}

	public function js($p,$min=0)
	{	
		return '<script src="'.$this->p2min($p,$min,'js').'?v='.VER.'"></script>';
	}

    public function p2min($path,$min,$ext)
    {
        if ($min) 
        {
            $path = str_replace('.'.$ext,'.min.'.$ext,$path);
            $path = str_replace('min.min','min',$path);
        }

        return $path;
    }








}
/*
<?php
MP::DEF('TPLDIR1',SYSDIR.'TPL'.DS);
MP::DEF('TPLDIR2',APPDIR.'TPL'.DS);
MP::DEF('LST_TPL',0);
MP::DEF('DBG_MGS',0);

MP::DEF('ST_DEV');
MP::DEF('ST_GLUE');

class TPL
{
	public $template='default.tpl',$prefix='default',$clear=0;
   public $js2bottom=1;
	public $tpl_arr=[],$vars=[],$txt,$declare_parts=[], $load_tpls = []; //зарегистрированные части css и js
	public $glue_arr = ['css'=>[],'js'=>[]];

	function __construct() {
       $this->tpl_arr = ['text'=>'','_html_'=>'','head_objects'=>'','meta_title'=>'','meta_keywords'=>'','meta_description'=>''];

       $this->txt = &$this->tpl_arr['text'];
       $this->vars = ['tpl_arr'=>&$this->tpl_arr,'tpl'=>&$this];
   	}

   	public function decl_p($inp)
   	{
   		$inp = MP_ARR::FROM_STR($inp);
   		while ($r = array_shift($inp)) $this->declare_parts[$r]=1;
   	}

   	public function js4part($p,$f,$EOL=false,$pre="",$glued=1)
    {
   		if (ST_GLUE && $glued) return $this->glue_reg('js',$p,$f);

   		return $this->st4p($p,$this->js($f),$EOL,$pre);
   	}

   	public function css4part($p,$f,$EOL=false,$pre="",$glued=1)
      {
   		if (ST_GLUE && $glued) return $this->glue_reg('css',$p,$f);

   		return $this->st4p($p,$this->css($f),$EOL,$pre);
   	}

   	public function glue_reg($type,$p,$url)
   	{
   		
   		$path = str_replace('/mp_pub',SYSDIR."PUB", $url);
   		$path = str_replace('/pub_data',WEBDIR."pub_data", $path);
   		switch ($type) {
   			case 'css':
   				$path = str_replace('.css','.min.css',$path);
   				break;
   			case 'js':
   				$path = str_replace('.js','.min.js',$path);
   				break;

   		}

   		$this->glue_arr[$type][] = 
         ['u'=>$url,'p'=>$path,'l'=> ((int)$this->if_decl_p($p))];
   	}

   	public function glue_stat()
   	{
   		$js_k = $css_k = "";
   		if (!ST_GLUE) return;

   		foreach ($this->glue_arr['js'] as $key => $it) $js_k.=$it['l'];
   		
   		foreach ($this->glue_arr['css'] as $key => $it) $css_k.=$it['l'];
   		

   	  $alph = [0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K',
        'L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','_','-','='];

         $js_k = int2alphabet($alph,bindec($js_k));
         $css_k = int2alphabet($alph,bindec($css_k));


   		$glue_path = WEBDIR."pub_data/static_cache/";

   		$js_path = $glue_path.$js_k.".js";
   		$css_path = $glue_path.$css_k.".css";

   		$glue_path = $alph = NULL;
   		unset($glue_path,$alph);

   		if (!is_file($js_path) && count($this->glue_arr['js']))
   		{
   			foreach ($this->glue_arr['js'] as $key => $it) 
   			{
   				if (!$it['l']) continue;*/
   				//$pre = '/* file: '.$it['u'].'*/'."\n";
   			/*	file_put_contents($js_path,$pre.file_get_contents($it['p'])."\n",FILE_APPEND);
   				
   			}
   		}

   		if (!is_file($css_path) && count($this->glue_arr['css']))
   		{
   			foreach ($this->glue_arr['css'] as $key => $it) 
   			{
   				if (!$it['l']) continue;*/
   				//$pre = '/* file: '.$it['u'].'*/'."\n";
   				/*file_put_contents($css_path,$pre.file_get_contents($it['p'])."\n",FILE_APPEND);
   				
   			}
   		}


   		$this->to_head($this->css('/pub_data/static_cache/'.$css_k.".css",1));
         $js_block = ($this->js2bottom) ? 'bottom_js' : 'head_objects';
         $this->add2block($js_block,$this->js('/pub_data/static_cache/'.$js_k.".js",1));

   	}

   	public function st4p ($p,$inc,$EOL=false,$pre="")
      {   
   		if($this->if_decl_p($p)) return $pre.$inc.(($EOL)?$EOL:'');
   	}

   	public function st_format($inp,$pre="",$EOL="")
   	{
   		preg_match_all('!(\<[^>]+\>)!simu',$inp,$res);
   		foreach ($res[1] as $key => $v) echo $pre.$v.$EOL;
   	}

   	public function undecl_p($inp)
   	{
   		if($this->if_decl_p($inp)) unset($this->declare_parts[$inp]);
   	}

   	public function if_decl_p($inp)
   	{
   		if ($inp===0) return true;
   		return (isset($this->declare_parts[$inp]));
   	}

   	//+++добавить несколько частей в массиве
   	public function part_css($part,$css)
   	{
   		 if ($this->if_decl_p($part)) 
            echo $this->css($css);
   	}
	
	public function display($prefix=false,$tpl_name="default")
	{
		global $tpl,$config,$db;

		if (LST_TPL) echo_arr($this->load_tpls);
      if (arr_v($_POST,'clear')=='yes') $this->clear=1;
	
		if($prefix===false) $prefix = $this->prefix;
		if ($this->clear) 
		{
			echo $this->tpl_arr['text'];
			return ($this->clear=0);
		}

		$tpl_arr = &$this->tpl_arr;		
		
		include($this->path($tpl_name,$prefix));
		if (DBG_MGS) include($this->path('debug_message','default'));
		return 1;
		
	}
	
	public function path($tpl_name,$prefix=false) 
	{		
		$p = ((empty($prefix))?$this->prefix:$prefix).DS.$tpl_name.'.tpl';
		if (is_file($p_=TPLDIR2.$p) || is_file($p_=TPLDIR1.$p)) 
         return str_replace('//','/',$p_); 
		exit($tpl_name.' - NOT FOUND!!!'); 
	}
	public function add2block($k,$v){
      $this->tpl_arr[$k] = (isset($this->tpl_arr[$k]))? $this->tpl_arr[$k]."\r\n".$v : $v;
      return $this;
   }

   public function canonical($url)
   {
      $this->to_head('<link rel="canonical" href="'.$url.'" />');
   }

   public function show_bl($n)
   {
      if (isset($this->tpl_arr[$n])) echo $this->tpl_arr[$n];
   }

   
	
	

	public function css($p,$nm=0)
	{      
		return '<link rel="stylesheet"  type="text/css" href="'.$this->p2min($p,$nm,'css').'?v='.MP_VER.'" />';
	}

	public function js($p,$nm=0)
	{	
		return '<script src="'.$this->p2min($p,$nm,'js').'?v='.MP_VER.'"></script>';
	}

   public function to_head($str)
   {
      return $this->add2block('head_objects',$str);
   }

	public function add_js ($inp,$no_mod=0){
		return $this->to_head($this->js($inp,$no_mod));
	}

	public function add_css ($inp,$no_mod=0){
		return $this->to_head($this->css($inp,$no_mod));
	}


	public function mp_css($inp)
	{
      $inp = MP_ARR::FROM_STR($inp);
		
		for ($i=0,$c=count($inp); $i < $c; $i++)  
			$this->add_css(M_PUB.'/css/'.$inp[$i]);

		return $this;
	}

	public function mp_js($inp)
	{
		$inp = MP_ARR::FROM_STR($inp);

		for ($i=0,$c=count($inp); $i < $c; $i++) 
			$this->add_js(M_PUB.'/js/'.$inp[$i]);

		return $this;
	}

   public function meta_tags ($i,$i2=false) 
	{
		if ($i===false && is_array($i2))
		{
			$i=[];
			list($i['meta_title'],$i['meta_keywords'],$i['meta_description']) = $i2;
		}
		$f = function($n) {return(htmlspecialchars($n));};
		$m_arr = SELECT_FROM_ARR('meta_description;meta_keywords',$i);
		$m_arr = array_map($f,$m_arr);
		//титл не экранируем
		$m_arr['meta_title'] = $i['meta_title'];
		unset($i);
		$this->tpl_arr = array_merge($this->tpl_arr,$m_arr);
		//echo_arr($m_arr);
		unset($f,$m_arr);
	}
	public function ograph($title,$description,$image,$url,$type="website")
	{
		$a = &$this->tpl_arr;

		$a['_html_'] = ' prefix="og: http://ogp.me/ns#"';
      $_arr = compact('title','type','url','description','image');

      foreach ($_arr as $p => $v) 
      {
        $v=htmlspecialchars($v);
        $this->to_head('<meta property="og:'.$p.'" content="'.$v.'"/>');
      }
	}
  		   
	public function fetch($t,&$vars=array(),$prefix=false,$cache_en=false)
	{
		if ($cache_en)
		{
			$cache_key = MPCACHE::gen_key_p(array($t,$vars,$prefix));
			$cache = MPCACHE::from_cache("tpl_cache",$cache_key);
			if($cache!==false) return $cache['content'];
		}	

		if (empty($t)||(mb_substr($t,0,1)=="#")) return '';
		
		if (is_array($this->vars))extract($this->vars); 
      if (is_array($vars))extract($vars);
		$tpl = &$this;

		$this->load_tpls[] = $__p  = $this->path($t,$prefix);

		
		ob_start();
		include($__p);
		
		if ($cache_en) 
		{
			$html = ob_get_clean();
			MPCACHE::to_cache("tpl_cache",$cache_key,['content'=>$html]);
			return $html;
		}
		return ob_get_clean();
	}

	public function txt($txt){$this->add2block('text',$txt);}
}
*/