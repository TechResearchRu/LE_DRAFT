<?php
/******************
*   LE Core       *
*****************/


/*core functions*/
function arr_v($arr,$field,$default=false)
{
    return isset($arr[$field]) ? $arr[$field] : $default;
}

function echo_arr(&$arr) {
	if (ISWEB) echo '<pre>';
	print_r($arr);
	if (ISWEB) echo '</pre>';
}


/*core class*/
class LE 
{
    public static $DB,$TPL,$CACHE,$QUERY_DATA_TYPE;
    
    
    public static function DEF ($constant_name,$val=false)
    {
        if (!defined($constant_name)) define($constant_name, $val);
    }




}

/*date prepare class*/
//prepare class
class PRE {
	public static $MASK = ['D' => '0-9', 'R' => 'а-яё', 'L' => 'a-z', 'S' => '\s'];
	public static $SH_MASK = [
        'UP' => MB_CASE_UPPER, 
        'DOWN' => MB_CASE_LOWER, 
        'U1' => MB_CASE_TITLE];


	public static function SQL($s) 
	{
		if (method_exists(LE::$DB, 'prepare')) return LE::$DB->prepare($s);
		return addslashes($s);
	}
	public static function DOWN($s)
	{
		return mb_convert_case($s, MB_CASE_LOWER);
	}
	public static function UP($s)
	{
		return mb_convert_case($s, MB_CASE_UPPER);
	}


	public static function SHIFT($s, $t) 
	{
		if (isset(PRE::$SH_MASK[$t])) {
			return mb_convert_case($s, PRE::$SH_MASK[$t]);
			
		}
		exit('shift err mask');
	}

	public static function f2int($n,int $m):int 
	{
		if (empty($n)) return 0;
		$n=(float)$n;
		
		$n*=pow(10,$m+1);
		return (int) Ceil(round($n)/10);
	}
	public static function F($s, $t) {
        $preg = strtr(preg_quote(trim($t), '!'), PRE::$MASK);
        return preg_replace('![^' . $preg . ']!iu', '', $s);
    }

	public static function UP1($s) {
		$s = PRE::SHIFT(trim($s),'DOWN');
		$w = preg_split('/\s+/', $s);

		if (isset($w[0]))
		{
			$w[0] = PRE::SHIFT($w[0],'U1');
			return implode(' ',$w);
		}
		return $s;

	}

	public static function INT($i):int {return (int)PRE::NUM($i);}
	public static function NUM($i) {return preg_replace('![^0-9]!', '', $i);}
	public static function DEC($i):float {
		$i= preg_replace('/[^\-0-9,.]/u', '', $i);
		return  (float)preg_replace('!([\-]?[0-9]+)[,.]?([0-9]+)?!', '$1.$2', $i);
	}
	public static function MONEY_OUT($i) {return money_format('%n', $i);}
	//удаляет двойные пробелы и табы
	public static function DSP($i)
	{
		return preg_replace('/\s{1,}/u', " ", $i);
	}
	public static function PLAIN_FORMAT($str,$one_str=0)
	{
		$str = PRE::DSP($str);
		if ($one_str)  $str = preg_replace('!([\n]*)!simu', '', $str);
		$str = preg_replace('![\s]*([,.])!simu', '$1', $str);
		$str = trim($str);
		return $str;
	}
	//подрезает строку по разрешенному лимиту
	public static function CROP($s,$l=0){return (($l>0)?mb_substr($s,0,$l):$s);}
}


/**приведение в алфавит $a числа $int */
function int2alphabet(array $a,int $int):string
{
	$cnt = count($a); //емкость алфавита
	$out="";
	while ($int>=$cnt) 
	{
		$out = ($a[($int % $cnt)]).$out;
		$int = intdiv($int, $cnt);
	}

	return $a[$int].$out;
}