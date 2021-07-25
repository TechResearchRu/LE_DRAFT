<?php
//LE::$ALC->logout();
LE::$ALC->set_lev(4);



//тут будет контроллер
LE::$TPL->mod_cont .= "<h1>Добро пожаловать в секретный раздел</h1>";

LE::$TPL->mod_cont .= "kokoko2222";

LE::$TPL->display();