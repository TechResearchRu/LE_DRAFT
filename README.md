# LE Framework Introduction
Перед вами простейший фреймворк, архитектура которого приспособлена для мультисайтовой раскатки. 
Т.е. у вас есть одна папка с фреймворком, который сразу и движок, а конкретные сайты или консольные приложения подключают файл `include.php`, создают файл конфигурации в папке приложения, при желании переопределяют или создают новые классы, модули и т.д., в итоге, с минимальными затратами можно слепить простейшее нестандартное приложение.

В будущем на этом фреймворке можно будет создавать такие приложения, как:
* Консольные демоны (службы)
* Блоги и статейные сайты
* Интернет-магазины

## Структура приложения
```
├── app_dir
│   ├── app_conf.php
│   ├── CLASSES - может отсутствовать
│   ├── MODULES - может отсутствовать
│   ├── TPL - может отсутствовать
│   ├── sessions - создается автоматически, если нет
│   ├── web - доступная веб-серверу папка
│   │   ├── assets - статичные файлы, не меняются приложением
│   │   │   ├── css
│   │   │   ├── js
│   │   │   └── etc
│   │   ├── pub_data - публикуемые ресурсы типа картинок
│   │   └── index.php - точка входа
```
### index.php - точка входа
```php
<?php

define("APPDIR",realpath(__DIR__."/../").DIRECTORY_SEPARATOR);
include "/www/kernel/init.php"; //path to framework
```

### app_conf.php
```php
<?php 
/*DB CONFIGURATION*/
SYSCONF::$DB['db_name'] = 'project1';
SYSCONF::$DB['user'] = 'root';
SYSCONF::$DB['pass'] = '...';
//SYSCONF::$DB['host'] = 'localhost';

//дефолтный модуль в корне сайта...
SYSCONF::$DEFAULT_MODULE['default']='welcome';

SYSCONF::$DEFAULT_MODULE['admin']='blog';




SYSCONF::$SPACE_LIST = [
    'admin|cabinet'=>'admin',
    'main'=>'main'
];

SYSCONF::$MOD_ALIASES['main'] = [
    'cart|category|product'=>'shop'
];

SYSCONF::$MOD_ALIASES['admin'] = [
    'category|cart|orders'=>'catalog'
];
```
