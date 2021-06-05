<?php

class LE_MYSQL 
{
    public $l,$cnt = 0,$debug=0,$echo_sql=0;
	private $cnf = [];

    function __construct(&$cnf) 
	{
		$this->cnf = &$cnf;
        if (defined("DEBUG") && DEBUG==1) $this->debug=1;

		$this->connect($cnf);
	}

    //exit and echo error
    protected function err($txt)
    {
        http_response_code(503);
        exit($txt);
    }


	/*****************************
	|  private helpers functions |
	*****************************/
    protected function create_connect()
	{
		if (isset($this->cnf['socket']) && !empty($this->cnf['socket']))
			return new mysqli(NULL, $this->cnf['user'], $this->cnf['pass'], $this->cnf['db_name'], NULL, 
            $this->cnf['socket']);

		return new mysqli($this->cnf['host'], $this->cnf['user'], $this->cnf['pass'], $this->cnf['db_name']);
	}

    public function connect()
	{
		$this->l = $this->create_connect();

		if ($this->l->connect_errno) $this->err("ERROR CONNECT DB");

		$this->sess_conf();
	}

    private function sess_conf()
	{
		$this->query("set names utf8");
		$this->query("SET @@session.time_zone = '+00:00'");
	}

    public function check_conn ($debug=0) 
	{
		if ($this->l->ping()!==false) return;
        //проверим еще раз не переподключился ли он автоматом
        usleep(500);
        if ($this->l->ping()===false) $this->connect();
	}

	/*****************************
	|  query & anwer functions   |
	*****************************/
    public function query($s, $buffer = true, $o = []) 
	{
		$this->cnt++; //счетчик запросов
		$buf = ($buffer) ? MYSQLI_STORE_RESULT : MYSQLI_USE_RESULT;
		$type = (isset($o['type'])) ? $o['type'] : $this->detect($s);

		if ($this->echo_sql) echo $s.BR;
		

		$res = $this->l->query($s, $buf);

		$e = ($this->l->error);
		if (!empty($e)) $this->err(($this->debug ? $e . ' <br>(' . $s . ')' : 'ERR QUERY!!!'));

		

		unset($e, $buf);
		return $this->answer($res, $type, $o);
	}

    private function detect($s) {
		if ((stripos($s, 'select', 0) !== false)) return 'S';
		if ((stripos($s, 'SHOW', 0) !== false)) return 'S';
		if ((stripos($s, 'insert', 0) !== false)) return 'I';
		if ((stripos($s, 'update', 0) !== false)) return 'U';
		if ((stripos($s, 'delete', 0) !== false)) return 'D';
		if ((stripos($s, 'truncate', 0) !== false)) return 'T';
	}

	private function answer(&$r, $t_, $o) {
		$t = &$this;
		$l = &$this->l;
		switch ($t_) {
		case 'S':
			if ((isset($o['row']) && $o['row']) || (isset($o['val']) && $o['val'])) 
            {
				$r_ = $this->get_row($r);
				$r->free_result();
				if (isset($o['val']) && $o['val']) return $r_[trim($o['val'])];
				return $r_;
			}
			return $r;
			break;
		case 'I':
			return $l->insert_id;
			break;
		case 'U';
		case 'T';
		case 'D';
			return $l->affected_rows;
			break;
		}
		return false;
	}

    public function get_row(&$r) 
	{
		return ($r) ? mysqli_fetch_assoc($r) : FALSE;
	}

	/*****************************
	|  query prepare functions   |
	*****************************/

    public function prepare($s) 
    {
        return $this->l->real_escape_string($s);
    }

    public function arr2in($ids,$str=false)
	{
		if ($str) array_map(function($id){return "'".$id."'";},$ids);
        
        $ids = (is_array($ids)) ? implode(',',$ids) : $ids;
		return $this->prepare($ids);
	}

	
	public function gen_set($arr) 
    {
		$arr_ = [];
		if (count($arr)) {
			foreach ($arr as $k => &$v) {
				$arr_[] = "`" . $k . "`='" . (($v === '###') ? '' : $this->prepare($v)) . "'";
			}
		}

		if (!count($arr_) > 0) {
			return false;
		}

		unset($arr);
		return implode(', ', $arr_);
	}

    /**********************
	|  result functions   |
	**********************/
    public function count(&$res)
	{
		if (!$res || gettype($res)!=="object") return false;
		return mysqli_num_rows ($res);
	}

    public function cnt(&$res) {return $this->count($res);}

    public function res2arr($res = 0,$key=false,$shift_reg=false) 
	{
		$res_arr = [];

		if($key===false)
			while($r = $this->get_row($res)) $res_arr[] = $r;
		else	
            while($r = $this->get_row($res)) 
            {
                    $_key = ($shift_reg) ? PRE::SHIFT($r[$key],$shift_reg) : $r[$key];
                    $res_arr[$_key] = $r;
            }
	
		
		return $res_arr;
	}

	public function found_rows() 
	{
		return $this->query("SELECT FOUND_ROWS() as `cnt`", false, ['val' => 'cnt']);
	}




    /**************** 
    QUERY GENERATORS
    ****************/
    public function INS($table, $val_arr) 
    {
		$table = $this->prepare($table);
        $set_str = $this->gen_set($val_arr);
        $sql = 'INSERT INTO `' . $table . '` SET ' . $set_str;
		return $this->query($sql,0,['type'=>'I']);
	}

	public function UPD($table, $val_arr, $id, $idf = 'id',$str_key=false) 
    {
		
		if (empty($id)) $this->err("ERR DB UPD");
        $table = $this->prepare($table);
        $set_str = $this->gen_set($val_arr);
        $id_field = $this->prepare($idf);
        $in_list = $this->arr2in($id,$str_key);

		$sql = 'UPDATE `' . $table . '` SET ' . $set_str . ' WHERE `' . $id_field . '` IN (' . $in_list . ')';

        return $this->query($sql,0,['type'=>'U']);
	}

	public function DEL($table, $id, $idf = "id",$str=false) 
    {
		if (is_array($id) && !count($id)) return false; //нечего удалять, если ничего не передали
		if (!is_array($id)) $id = [$id];
        
        $table = $this->prepare($table);
        $id_field = $this->prepare($idf);
        $in_list = $this->arr2in($id);

		$sql = 'DELETE FROM `' . $table . '` WHERE `' . $id_field . '` IN(' . $in_list.')';
        return $this->query($sql,0,['type'=>'D']);

	}
	//ins or upd return index
	public function SAVE($t,$v,$idf="id")
	{
		if (!isset($v[$idf]) || $v[$idf]==0) return $this->INS($t,$v);

		$id = $v[$idf]; unset($v[$idf]);
        
        //upd
		if ($id>0 && count($v)>0) $this->UPD($t,$v,$id,$idf);
        //delete
		if ($id<0) $this->DEL($t,($id*-1),$idf);
	
		return $id;
	}

	/*
	public function SELECT($tbl,$fields,$where,$order)
	{
		$fields = $this->gen_f($fields);
		$tbl = $this->prepare($tbl);



		$sql = "SELECT f1, f2 FROM table WHERE a=5 ORDER BY f1 DESC LIMIT 1"
		if ($fields===0) $f="*";
		else 
		{

		}
	}*/


    /*****************
    * custom queries *
    *****************/

    public function query_arr($sql,$key=false,$shift_reg=0)
	{
		$res = $this->query($sql,0,['type'=>'S']);
		$rez = $this->res2arr($res,$key,$shift_reg);
		$res->free();
		return $rez;
	}

	public function query_keyval($sql,$k=false,$v=false)
	{
		$k = trim($this->prepare($k));
		$v = trim($this->prepare($v));
 
		$res = $this->query($sql,0,['type'=>'S']);
		$res_arr = [];
		while($r = $this->get_row($res)) $res_arr[$r[$k]] = $r[$v];

		return $res_arr;
	}

    public function query_single($s) {
        return $this->query($s, false, ['row' => true]);
    }

    public function query_val($s,$v)
    {
        return $this->query($s, false, ['val' => $v]);
    }

	private function gen_f($f="*")
	{
		if (trim($f)=="*" || $f===0 || $f===false) return "*";
		$f = explode(',',$f);
		$cnt = count($f);

		for($i=0;$i<$cnt;$i++) $f[$i] = '`'.trim($f[$i]).'`';

		return implode(', ',$f);


	}

}