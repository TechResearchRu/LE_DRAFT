<?php
class LE_MOD_LOAD {
    public $m1=false,$m2=false,$space_list=[],$mod_aliases=[],$space=false;
    public $init_path=false,$mod_path=false,$url;

    function __construct($autorun=1)
    {
        $this->m1=SYSDIR."MODULES".DS;
        $this->m2=APPDIR."MODULES".DS;
        $this->space_list = SYSCONF::$SPACE_LIST;
        $this->mod_aliases = SYSCONF::$MOD_ALIASES;
        if ($autorun) $this->parse_url();
    }

    public function parse_url()
    {
        $query =  LE_REQUEST::url2arr()['query_clr'];        
        
        $spaces_str = implode('|',array_keys($this->space_list));


        preg_match('!^/('.$spaces_str.')?/?([^/?]*)/?(.*?)$!simu', $query,$res);

        $this->url = $res;
        $space_in_q = $res[1];
        $mod_in_q = $res[2];
        
        $space=SYSCONF::$DEFAULT_MODSPACE; //default

        if (!empty($space_in_q)) 
        {
            $space = $this->search_key($this->space_list,$space_in_q);
            if ($space===false) return false;
        }


        if ($this->select_path($space)===false) return false;

        $this->space = $space;

        $mod=arr_v(SYSCONF::$DEFAULT_MODULE,$space,false); //default
        if (!empty($mod_in_q)) 
        {
            $mod_rr=false;
            if (isset($this->mod_aliases[$space]))
                $mod_rr = $this->search_key($this->mod_aliases[$space],$mod_in_q);
           
            $mod = ($mod_rr) ? $mod_rr : $mod_in_q;
        }
        $this->init_path = $this->select_path($space,"__space_init.php");
        $this->mod_path = $this->select_path($space,$mod.".php");

    }


    public function select_path($space,$path="")
    {
        $app_path = $this->m2.$space.DS.$path;
        $sys_path = $this->m1.$space.DS.$path;
        
        if (file_exists($app_path)) return $app_path;
        if (file_exists($sys_path)) return $sys_path;
        return false;
    }

    public function search_key($arr,$key)
    {
        foreach ($arr as $tpl => $val)
            if(preg_match('!^('.$tpl.')$!simu', $key)) return $val;

        return false;
    }
}