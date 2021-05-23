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