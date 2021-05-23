<?php

class CONTR extends LE_MOD_CONTROLLER {
    
    protected function check_dest_folder($f)
    {
        if (!is_dir($uploaddir)) 
            if(mkdir($uploaddir,0777,true)===false)
                return false;
        
        return true;

    }
    protected function _ajx_add_img($data)
    {
        if (!isset($_FILES['upload'])) return false;
        $dest = WEBDIR.'pub_data/upload/img/';        
        if (!$this->check_dest_folder($dest)) return false;
        $filename = LE_FS::SAVE_POST(['f_name'=>'upload','path'=>$dest]);
        return ['url'=>'/pub_data/upload/img/'.$filename, 'as_is'=>1];
    }

    protected function _ajx_save_content($data)
    {
        $id = PRE::INT($data['id']);
        
        $html_cont = $data['html_cont'];
        preg_match('!(<h1>(.*?)<\/h1>)?(.*)!simu',$html_cont,$out);
        $html_cont = $out[3];
        $html_head = trim($out[2]);
        //return;

        $save_data = ['id'=>$id,'html'=>$html_cont,'head'=>$html_head];

        $id = LE::$DB->SAVE('text_content',$save_data);

        return $id;
    }

    protected function _ajx_remove_it($inp)
    {
        if (!isset($inp['id'])) return false;
    
        $id=PRE::INT($inp['id']);
        if (!$id>0) return false;
        $res = LE::$DB->DEL('text_content',$id);
        return ($res>0);
    }

    protected function _inp_default($inp)
    {
        $res = LE::$DB->query_arr("SELECT * FROM `text_content`",'id');

        $to_tpl['cont_list'] = $res;
        return LE::$TPL->fetch('blog/list',$to_tpl);
    }

    protected function _inp_edit($inp)
    {
        $id = PRE::INT($inp);
        if ($id>0) 
        {
            $res = LE::$DB->query_single("SELECT * FROM `text_content` WHERE `id`=".$id);
            $it_data = json_decode($res['data'],1);
        }
        else 
        {
            $res = ['html'=>'','head'=>''];
            $it_data = [];
            $id=0;
        }

        $to_tpl = [
            'data'=>$it_data,
            'id'=>$id,
            'html_cont'=>$res['html'],
            'head'=>$res['head']
        ];

        return LE::$TPL->fetch('blog/edit_item',$to_tpl);
    }
}




include CLSDIR."blog.php";
$blog_model = new blog_model;


$controller = new CONTR($le_mod_loader->url,$blog_model);


LE::$TPL->mod_cont .= $controller->start();