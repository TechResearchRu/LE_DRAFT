<?php
/**
 *  Author: Pavel Belyaev
 *  GitHub: https://github.com/TechResearchRu/LE_DRAFT
 *  Email: pavelbbb@gmail.com
 *  LE FRAMEWORK, LE_TIME v0.1 2021, read strftime php doc
 */

class LE_TIME
{
    public static $TZ=5; //локальный часовой пояс, можно меняться для пользователя, используется при отображении данных

    public static function NUM2FORMAT($num)
    {
        $num-=1;
        $arr = ['%Y-%m-%d %H:%M:%S','%Y-%m-%d','%d %b %Y - %T','%d %b %Y %T','%d.%m.%Y - %T','%d.%m.%Y %T','%d.%m.%Y','%d %b %Y'];

        if (isset($arr[$num])) return $arr[$num];
        return false;
    }
    
    public static function TS2STR($f=false,$ts=false,$tz=false)
    {
        if ($f!==false && is_numeric($f)) 
        $f = LE_TIME::NUM2FORMAT($f);
        
        
        $f = ($f===false)?'%d %b %Y - %T':$f; //format
        
        $tz = ($tz===false) ? LE_TIME::$TZ : $tz; //time zone
        $ts = ($ts===false) ? time() : $ts; //timestamp

        $ts+= 3600*$tz; //correct timezone
  
        

        return gmstrftime ($f,$ts);
    }

    public static function STR2TS($str)
    {
       return strtotime ($str); 
    }

    public static function STR2STR($str,$f=false,$tz=0)
    {
        $ts = LE_TIME::STR2TS($str);
        return LE_TIME::TS2STR($f,$ts,$tz);
    }

    public static function STR2ARR($str)
    {
        $ts = LE_TIME::STR2TS($str);
        return LE_TIME::TS2ARR($ts);
    }

    public static function TS2ARR($ts=false)
    {
        $res = LE_TIME::TS2STR('%Y:%m:%d:%H:%M:%S',$ts,0);
        $res = explode(':',$res);
        return [
            'Y'=>$res[0],'M'=>$res[1],'D'=>$res[2],
            'HOUR'=>$res[3],'MIN'=>$res[4],'SEC'=>$res[5]
        ];
    }

    public static function ARR2TS($arr)
    {
        $str = $arr['Y']."-".$arr['M']."-".$arr['D'];
        $str .= " ".$arr['HOUR'].":".$arr['MIN'].":".$arr['SEC'];
        return LE_TIME::STR2TS($str);
    }

    public static function ARR2STR($arr,$f=false,$tz=0)
    {
        $ts = LE_TIME::ARR2TS($arr);
        return LE_TIME::TS2STR($f,$ts,$tz);
    }
    
}