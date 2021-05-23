<?php
//модель для блога
class blog_model 
{
    public $table = "text_content";

    function __construct($table=false)
    {
        //custom table in db
        if ($table!==false) $this->table = $table;
    }

    public function get_it($id)
    {
        $res = LE::$DB->query_single("SELECT * FROM `".$this->table."` WHERE `id`=".$id);
        $res['data'] = json_decode($res['data'],1);
        return $res;
    }

    public function get_list($inp=false)
    {
         return LE::$DB->query_arr("SELECT * FROM `".$this->table."`",'id');
    }

    public function save_it($id,$data=[])
    {
        $data['id']=PRE::INT($id); 

        return LE::$DB->SAVE($this->table,$data);
    }

    public function rem_it($id=0)
    {
        $id=PRE::INT($id);
        if (!$id>0) return false;
        $res = LE::$DB->DEL($this->table,$id);
        return ($res>0);
    }

    protected function check_dest_folder($f)
    {
        if (!is_dir($f)) 
            if(mkdir($f,0777,true)===false)
                return false;
        return true;
    }
    
    public function add_img()
    {
        if (!isset($_FILES['upload'])) return false;
        $dest = WEBDIR.'pub_data/upload/img/';        
        if (!$this->check_dest_folder($dest)) return false;
        return LE_FS::SAVE_POST(['f_name'=>'upload','path'=>$dest]);
    }

}