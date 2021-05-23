<?php
class CONTR extends LE_MOD_CONTROLLER {
       
    protected function _ajx_add_img($data)
    {
        if( ($filename = $this->model->add_img()) !==false ) 
            return ['url'=>'/pub_data/upload/img/'.$filename, 'as_is'=>1];
        return false;
    }

    protected function _ajx_save_content($data)
    {
        $id = PRE::INT($data['id']);
        $html_cont = $data['html_cont'];
        preg_match('!(<h1>(.*?)<\/h1>)?(.*)!simu',$html_cont,$out);
        return $this->model->save_it($id,['html'=>$out[3],'head'=>trim($out[2])]);
    }

    protected function _ajx_remove_it($inp)
    {
        if (!isset($inp['id'])) return false;
        return $this->model->rem_it($inp['id']);
    }

    protected function _inp_default($inp)
    {
        $data = $this->model->get_list();
        $to_tpl['cont_list'] = $data;
        return LE::$TPL->fetch('blog/list',$to_tpl); 
    }

    protected function _inp_edit($inp)
    {
        $id = PRE::INT($inp)+0;
        $res = ($id>0) ? $this->model->get_it($id) : ['html'=>'','head'=>'','data'=>[]];
    
        $to_tpl = [ 'data'=>$res['data'], 'id'=>$id, 'html_cont'=>$res['html'], 'head'=>$res['head']];
        return LE::$TPL->fetch('blog/edit_item',$to_tpl);
    }
}

include CLSDIR."blog.php";
$blog_model = new blog_model;

LE::$TPL->mod_cont .=  (new CONTR($le_mod_loader->url,$blog_model))->start();