<?php
/* LE Framework autoload core classes */
spl_autoload_register(function ($class_name) 
{
	if (is_file(CORE_CLSDIR.$class_name.".php")) include CORE_CLSDIR.$class_name.".php";	
});