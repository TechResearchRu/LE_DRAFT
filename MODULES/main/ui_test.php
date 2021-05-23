<?php
/*
1. Подключить нужные для модуля классы и конфиги
2. Определить какой метод контроллера выполнить и запустить его
3. Внутри метода идет вывод или запись данных, при выводе передается в шаблон
4. Содержимое

*/

//compute
//if (isset($_POST)) echo_arr($_POST);
//if (isset($_FILES)) echo_arr($_FILES);


if (isset($_FILES['upl_img']))
{
    $uploaddir = WEBDIR.'pub_data/upload/img/';
    $f_name=basename($_FILES['upl_img']['name']);
    $uploadfile = $uploaddir . basename($_FILES['upl_img']['name']);
    if (move_uploaded_file($_FILES['upl_img']['tmp_name'], $uploadfile)) 
    {
        $out = ['success'=>1];
        $out['data'] = ['url'=>'/pub_data/upload/img/'.$f_name];
        $mod_out = json_encode($out);
    }
}


else
$mod_out = LE::$TPL->fetch('le_ui_kit/test1');

//out to tpl
LE::$TPL->mod_cont .= $mod_out;