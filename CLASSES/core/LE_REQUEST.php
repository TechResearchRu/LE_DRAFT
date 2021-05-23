<?php 
class LE_REQUEST {
    public static function url2arr($s=false, $use_forwarded_host = false)
    {
        if ($s===false) $s = $_SERVER;
        $res = [];

        $ssl = (isset($s['HTTPS']) && $s['HTTPS'] == 'on' );
        $port = (isset($s['SERVER_PORT'])) ? $s['SERVER_PORT'] : '80';
        
        
        //хак для ssl nginx->apache
        /*
        if ((!$ssl) && function_exists('apache_request_headers'))
        {
            $h = apache_request_headers();
            if (is_array($h) && isset($h['Nginx-Https']) && $h['Nginx-Https']=='on')
            {
                $ssl=true; $port=443;
            }
        }
        */

        $protocol  = strtolower($s['SERVER_PROTOCOL']);
        
        $scheme = substr( $protocol, 0, strpos( $protocol, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $standart_port =  ((!$ssl && $port=='80') || ($ssl && $port=='443'));
        
        $host="locahost";
        if ($use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ))
            $host = $s['HTTP_X_FORWARDED_HOST'];
        elseif (isset( $s['HTTP_HOST']))
            $host = $s['HTTP_HOST'];
        elseif (isset( $s['SERVER_NAME']))
            $host = $s['SERVER_NAME'];

        

        $host_full = ($standart_port) ? $host : ($host.":".$port);

        $query= isset($s['REQUEST_URI']) ? $s['REQUEST_URI'] : '';

        $query_clr =  preg_replace('!\?.*?$!','',$query);
        

        return compact('ssl','port','scheme','standart_port','host','host_full','query','query_clr','protocol');
    }


    public static function TYPE_DETECT()
    {
        if (!isset($_SERVER["CONTENT_TYPE"])) return false;

        $type = trim(explode(';',$_SERVER["CONTENT_TYPE"])[0]);
        $type = PRE::DOWN($type);


        if ($type=='application/json') return 'json';

        return false;
    }



    public static function get2str($cust_get=null)
    {
        $get= (is_null($cust_get)) ? $_GET : $cust_get;

        if (!is_array($get) || !count($get)) return '';

        $arr = [];
        foreach ($get as $k => $v) 
            $arr[] = $k.'='.$v;

        return '?'.implode('&',$arr);
    }



    public static function str2get($q="")
    {
        if(empty($q)) return false;
        $q = explode('&',$q);
        $out=[];
        $c = count($query);

        for ($i=0;$i<$c;$i++)
        {
            $r=explode('=',$q[$i]);
            if(!isset($r[1])) $r[1]='';
            $out[$r[0]]=$r[1];
        }
        
        return $out;
    }

    public static function MOVE($u)
    {
        http_response_code(301);
        header("Location: ".$u);
        exit();
    }

    public static function FIX_URLCASE($u)
    {
        $q = arr_v($u,'query','');
        if(empty($q)) return false;

        if (PRE::SHIFT($q,'DOWN')!=$q)
            LE_URL::MOVE(PRE::SHIFT($u['full'],'DOWN'));
    }


}