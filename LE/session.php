<?php
ini_set('session.gc_maxlifetime', SYSCONF::$SESS_TIME);
ini_set('session.cookie_lifetime', SYSCONF::$SESS_TIME);
ini_set('session.save_path', SYSCONF::$SESS_DIR);

if (!is_dir(SYSCONF::$SESS_DIR)) mkdir(SYSCONF::$SESS_DIR,0700,true);

session_start();

//при первом входе знать откуда пришел человек
if (!isset($_SESSION['ref']) && isset($_SERVER["HTTP_REFERER"])) 
        $_SESSION['ref'] = $_SERVER["HTTP_REFERER"];

//каждые 3 минуты делаем обновление времени жизни сессии
$_exp = time()+SYSCONF::$SESS_TIME;
if (!isset($_SESSION['_exp'])) 
        $_SESSION['_exp'] = $_exp;
elseif (($_exp-$_SESSION['_exp'])>180)
        setcookie ("PHPSESSID", session_id() , ($_SESSION['_exp']=$_exp) ,'/');