<?php
if (SYSCONF::$USE_MYSQL) 
    LE::$DB = new LE_MYSQL(SYSCONF::$DB);

//+++add sqlite