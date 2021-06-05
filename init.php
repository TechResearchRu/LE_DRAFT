<?php
/********************************************
*  LE Framework v0.1  | 18.04.2021          *
*  Lite Elephant (PHP) Framework            *
*  Elizavet , Ural State, Russia            *
*  Thanks to many open source projects      *
*  read copyrights.txt file                 *
*  ---------------------------------------- *
*  by Pavel Belyaev   | pavelbbb@gmail.com  *
*  Tech-Research.Ru                         *
*********************************************/
if (!defined("APPDIR") || !is_dir(APPDIR)) exit ("APPDIR not defined");

define("DS",DIRECTORY_SEPARATOR); //directory separator (/ or \) 
define("SYSDIR", __DIR__.DS); // system dir
define("I", SYSDIR."LE".DS); //include sys files

require I."deprecated.php";
require I."core.php";

LE::DEF("VER","0.1.0");
LE::DEF("CLI"); //console mode 0
LE::DEF('BR',(CLI?PHP_EOL:'<br/>')); //перенос строк 
LE::DEF('ISWEB',(!CLI)); //web mode 1
LE::DEF('WEBDIR',APPDIR."web".DS);

define('CLSDIR',	SYSDIR.'CLASSES'.DS); //class sys
define('CORE_CLSDIR',	CLSDIR.'core'.DS); //core class sys
define('CLSDIR2',	APPDIR.'CLASSES'.DS); //class app

require I."sys_conf.php";

include I."sys_autoload.php"; 
include I."db_init.php"; //mysql

if (ISWEB):
    include I."session.php";
    LE::$TPL = new LE_TPL; //шаблонизатор
    LE::$ALC = new LE_ALC; //контроль доступа
    include I."load_mod.php"; //load module
    LE::$TPL->display();
    exit();
else:
    LE::DEF('SDOM', 'localhost');
endif;