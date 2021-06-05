<?php
$le_mod_loader = new LE_MOD_LOAD();
LE::$QUERY_DATA_TYPE = LE_REQUEST::TYPE_DETECT();
LE::$FULL_URL = LE_REQUEST::url2arr()['full_url'];


//init space
if ($le_mod_loader->init_path!==false) 
    include $le_mod_loader->init_path;
//load mod
if ($le_mod_loader->mod_path!==false) 
    include $le_mod_loader->mod_path;
//not found
if ($le_mod_loader->mod_path==false) 
    include $le_mod_loader->select_path('main','__404.php');

