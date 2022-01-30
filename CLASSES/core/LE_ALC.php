<?php
/**
 *  Author: Pavel Belyaev
 *  GitHub: https://github.com/TechResearchRu/LE_DRAFT
 *  Email: pavelbbb@gmail.com
 *  LE FRAMEWORK, LE_ALC v0.1 2021, Access Level Control
 */

class LE_ALC
{
    private $table="sys__accounts";
    private $need_lev=0;


    public function set_lev($need_lev)
    {
        return $this->lev_control($need_lev);
    }



    public function lev_control($need_lev)
    {
        if (!$need_lev>0) return true;
        $lev = (isset($_SESSION['user']['level'])) ? $_SESSION['user']['level'] : 0;
        
        if (!$lev>0) return $this->auth($need_lev);
        
        if ($lev<$need_lev)
        {
        	http_response_code(403);
        	exit ('ACCESS DENIED!!!');
        }
        return $lev;
    }

    public function auth($need_lev)
    {
      $vars = ['act_url'=>LE::$FULL_URL];

      if(isset($_POST['login_ok'])) 
      {
          $res = $this->login($_POST);
          if ($res===200) return $this->lev_control($need_lev);

          if ($res===2) $vars['err']='Необходимо заполнить поля!';
          if ($res===3) $vars['err']='Пользователя с таким логином и паролем не существует!';
      }

      return $this->auth_form($vars);
    }

    private function auth_form($vars)
    {
        http_response_code(401);
        LE::$TPL->fetch2mcont('sys/auth',$vars,'main')->display();
        //LE::$TPL->mod_cont .= LE::$TPL->fetch('sys/auth',$vars,'main');
        //LE::$TPL->display();

        exit();
    }

    public function login($in)
    {
      if (!is_array($in)) return 2;
      $login = arr_v($in,'login');
      $password = arr_v($in,'password');

      $login = PRE::F($in['login'],'DRL@_-.');
      if (empty($login) || empty($password)) return 2;


      $sql = "SELECT * FROM `".$this->table."` WHERE `login`='".$login."'";
      $res = LE::$DB->query_single($sql);

      if (is_null($res) || $res['password']!==md5($password)) return 3;

      $_SESSION['user'] = ['uid'=>$res['id'],'level'=>$res['level']];
      
      return 200;
      
    }


    public function logout()
    {
        unset($_SESSION['user']);
    }
}