<?php

class LE_PHONE
{
	public static function prepare($inp)
	{
		$inp = str_replace(";", ",",Preg_replace(".[^0-9,;].", "", $inp));
		if (count($arr = explode(',',$inp))>1) $inp=$arr;


		if (is_array($inp))
		{	
			for ($i=0;$i<count($inp);$i++) $inp[$i]=self::format($inp[$i]);
			return implode(', ', $inp);
		}
		else
			return self::format($inp);

	}


	public static function clear($num)
	{
		$num = str_replace(";", ",",Preg_replace(".[^0-9,;].", "", $num));
		$arr = explode(',',$num);
		$cnt = count($arr);
		//print_r($arr);
		if ($cnt>1) 
		{
			for ($i=0;$i<$cnt;$i++) $arr[$i] = self::mobile_prepare($arr[$i]); return implode(',',$arr);
		}
		
		return self::mobile_prepare($num);
	}

	public static function mobile_prepare($num)
	{
		return preg_replace('/^[+]{0,1}[78]{0,1}9/','89',$num);
	}


	public static function format($num)
	{
		$num = self::mobile_prepare($num);
		$n=strlen($num = Preg_replace(".[^0-9].", "", $num));
		if ($n==6) return preg_replace("/([0-9]{2})([0-9]{2})([0-9]{2})/", "$1-$2-$3", $num);
		if ($n==7) return preg_replace("/([0-9]{3})([0-9]{2})([0-9]{2})/", "$1-$2-$3", $num);
		if ($n==10) return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})/", "($1)$2-$3-$4", $num);
		if ($n==11) return preg_replace("/([0-9])([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})/", "$1($2)$3-$4-$5", $num);
		return $num;
	}

}