<?php

class CONTR extends LE_MOD_CONTROLLER {
    
    protected function _ajx_upload_img($data)
    {
        if (!isset($_FILES['upl_img'])) return false;
        $uploaddir = WEBDIR.'pub_data/upload/img/';
        if (!is_dir($uploaddir)) 
            if(mkdir($uploaddir,0777,true)===false)
                return false;

        $filename = LE_FS::SAVE_POST(['f_name'=>'upl_img','path'=>$uploaddir]);

        return ['url'=>'/pub_data/upload/img/'.$filename];
    }

    protected function _ajx_save_content($data)
    {
        $md_cont = $data['md_cont'];

        $id = PRE::INT($data['id']);
        
        $_data = json_encode(['md_cont'=>$md_cont]);
        $html_cont = $data['html_cont'];

        $save_data = ['id'=>$id,'data'=>$_data,'html'=>$html_cont];

        $id = LE::$DB->SAVE('text_content',$save_data);

        return $id;
    }

    protected function _inp_default($inp)
    {
        $res = LE::$DB->query_arr("SELECT * FROM `text_content`",'id');

        $to_tpl['cont_list'] = $res;
        return LE::$TPL->fetch('le_ui_kit/editor_list',$to_tpl);
    }

    protected function _inp_edit($inp)
    {
        $id = PRE::INT($inp);
        if (!$id>0) return false;

        $res = LE::$DB->query_single("SELECT * FROM `text_content` WHERE `id`=".$id);
        $it_data = json_decode($res['data'],1);
        $to_tpl = compact('it_data','res','id');
        $to_tpl['md_cont'] = (isset($it_data['md_cont'])) ? $it_data['md_cont'] : '';
        return LE::$TPL->fetch('le_ui_kit/test_ckeditor',$to_tpl);
        //return LE::$TPL->fetch('le_ui_kit/test_editor',$to_tpl);
    }
}



        
//echo_arr($le_mod_loader->url);

$controller = new CONTR($le_mod_loader->url);
//$mod_out = $controller->start();





LE::$TPL->mod_cont .= $controller->start();