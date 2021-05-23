<?php
if(!defined("I")) die('err c001');

if(setlocale(LC_ALL, 'ru_RU.UTF-8','Russian_Russia.65001')===false) 
        exit('!!!not find UTF-8 LOCALE');
if(setlocale(LC_NUMERIC, 'en_US.UTF-8', 'C.UTF-8','C')===false) 
        exit('!!!not find C NUMERIC LOCALE');

mb_internal_encoding("UTF-8");
date_default_timezone_set('UTC');

LE::DEF('NO_CACHE'); //по-умолчанию кеширование включено


class SYSCONF 
{
public static $DEFAULT_MODSPACE = "main";
public static $DEFAULT_MODULE = ['main' => 'welcome','admin'=>'dashboard'];
public static $MOD_ALIASES;
public static $USE_MYSQL = TRUE;
public static $USE_TPL = TRUE;
public static $ADMIN_MAIL = '';
public static $ROBOT_MAIL = '';
public static $DISP_TIME = FALSE;
public static $DB = ['host'=>'localhost','user'=>'','pass'=>'','db_name' =>''];

public static $SPACE_LIST=['admin|cabinet'=>'admin','main'=>'main'];
public static $SESS_DIR;
public static $SESS_TIME=120960;
public static $MPV;
public static $DR_N="LE CMS";
public static $CACH_DIR;
}

SYSCONF::$SESS_DIR = APPDIR.'sessions'.DS;
SYSCONF::$CACH_DIR = APPDIR.'cache'.DS;

if (is_file(APPDIR.'app_conf.php')) include APPDIR.'app_conf.php';