# LE Framework Introduction

## Предупреждение
>Данный продукт не является коммерческой версией, всё публикуется на уровне AS IS,
>цель всего проекта - обучение и просто по фану слепить что-то, для коммерческих приложений
>используйте что-то типа Laravel, Yii и подобные фреймворки, тут просто игрушка
>
>вполне возможно что из этого что-то выйдет пригодное к жизни, но сейчас тут просто публикуются примеры кода из видеоуроков с Youtube
>
>**Основная работа автора не связана с веб-разработкой**, но я пилю всякое такое ради спортивного интереса...
>
>весь публикуемый код не претендует на звание эталона, **это просто велосипед ради развлечения!**

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

пример создания структуры
```bash
cd my_app
mkdir -p {CLASSES,MODULES,TPL,sessions,web} web/assets/{css,js,etc} web/pub_data
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

## Nginx Example config
```nginx
#project1.loc
server {
	listen 80;
	default_type text/html;
	server_name project1.loc;
	root /www/projects/project1/web;

    location ~* ^/pub/(.+\.(?:gif|jpe?g|png|js|css|woff|ttf|svg|eot|html|htm|txt))$
    {
         alias /www/kernel/PUB/$1;
         access_log off;
         expires 10d;
    }

	index index.php;

	location ~* ^.+\.(txt|jpe?g|gif|png|ico|css|txt|bmp|rtf|js|svg|eot|ttf|woff|html?)$
	{
         access_log off;
         add_header Cache-Control "public, max-age=31536000, immutable";
	}
	
	#все запросы направить на index.php
	location / {
		rewrite ^/(.*)$ /index.php;
	}

	location  ~ \.php$ {
		include snippets/fastcgi-php.conf;
		fastcgi_pass unix:/run/php/php7.3-fpm.sock;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
	}


}
```


## ToDo
* add class **LE_SQLITE** - иногда нужно делать мини-приложения типа домашней бухгалтерии
* add class **LE_CALENDAR** - простейшие операции с датами, удобно для формирования всяких графиков платежей, например для кредитных калькуляторов
* add class **LE_FORMGEN** - генератор типовых формочек с заполнением полей данными из БД, удобно для быстрого клепания редакторов в админке
* add class **LE_XML**, **LE_CSV** - чтение и генерация форматов для экспорта/импорта
* внедрить сторонние библиотеки для работы с xls, xlsx
* слепить простейший UIKIT, должен состоять как из CSS, так и из JS и бэкендные функции на PHP для генерации всяких модальных окон, нужно еще переопределить всякие алерты и конфирмы на свои, чтобы все вписывалось в интерфейс


> Весь процесс разработки логируется на [YouTube в виде видеоуроков](https://www.youtube.com/watch?v=hEfP0tYnmd4&list=PL0WBDVO8h9xcHuyw19JnOVjbxS-p6X3VF)
