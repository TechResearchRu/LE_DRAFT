# LE_CURL
&copy; by Pavel Belyaev, LE Framework, Tech-Research.ru

## About
Данный класс создан для выполнения http запросов, удобно для работы с RestApi, для скачивания любого контента по сети итд...

Я использую этот класс для работы с Api яндекс облака, VKApi и например для мониторинга страниц сайта (какие коды отдает, корректен ли ответ), CURL вообще очень удобная штука, этот класс лишь обертка для удобства

> Для корректной работы требуются модули iconv и curl

## How to use

### Инициализация
> Данный класс в автозагрузке, файл можно не подключать

```php
$CL = new LE_CURL;
```

### Настройки
```php
$CL->timeout = 90; //default=40
$CL->debug=1; //default 0
$CL->user_agent="MyBrowser"; // default = chrome 24
$CL->encode="cp1251"; // при указании перекодирует из указанной кодировки в utf8, по умолчанию пытается определить из заголовка
$CL->cook_file="/path/to/cook.txt"; //если не указать, то куки не будет принимать
```

### Выполнение запросов
#### Простой запрос GET

```php
$params = [
  'url'=>'https://example.com/index.php',
  'fer'=>'https://fromsite' // указание HTTP_REFERRER
];

$html = $CL->query($params);
```

или вместо массива можно сразу строку передать, если других параметров не будет

```php
$html = $CL->query("https://example.com/index.php");
```

#### Получение redirect_url
Данное действие иногда нужно для того, чтобы распарсить URL на который будет переадресован запрос, к примеру ВК всякие токены выдает редиректом на указанную страницу с дописыванием параметров


```php
$params = [
  'url'=>'https://example.com/index.php',
  'get_redirect'=>1
];

$CL->query($params);

$url = $CL->redirect;

```

#### Отправка простого POST (form)

```php
$post = ['field1'=>'val1','field2'=>'val2'];

$params = [
  'url'=>'https://example.com/index.php',
  'post'=>$post
];

$res = $CL->query($params);

```

#### Отправка другими методами, например, PUT
```php
$post = ['field1'=>'val1','field2'=>'val2'];

$params = [
  'url'=>'https://example.com/index.php',
  'post'=>$post,
  'method'=>'PUT'
];

$res = $CL->query($params);

```

#### Отправка не в полях POST, а, например, JSON
```php
$data = "{'field1':'val1','field2':'val2'}"; //юзайте json_encode

$params = [
  'url'=>'https://example.com/index.php',
  'raw'=>$data,
  'method'=>'PUT' //указание метода обязательно, например POST
];

$res = $CL->query($params);

```

#### Отправка файлов через POST
> не тестировал еще
```php
$file1 = curl_file_create($filename ,$mime_type = null , $posted_filename = null );

$post = ['field1'=>'val1','field2'=>'val2','image'=>$file1];

$params = [
  'url'=>'https://example.com/index.php',
  'post'=>$post,
  'headers' => ["Content-Type" => "multipart/form-data"] 
];

$res = $CL->query($params);

```

#### Сохранение в файл, например, скачать картинку или страницу сайта в файл
```
$CL->to_file($params,$path,$filename);
```
* `$params` - может быть строкой с url или списком параметров, вдруг вам нужно заголовки или REFERRER передать свой
* `$path` - папка сохранения
* `$filename` - имя файла в который сохранится, если не передано, то определит из url



### Параметры выполнения запросов

Параметр  | Описание | Тип
------------- | ------------- | ---------
url  | Полный url с указанием протокола | string
headers  | Заголовки запроса | array
get_redirect  | Получение url редиректа | bool
method  | указание метода (PUT,DEL,POST,GET) | string
post  | Поля POST | array
raw | Данные отправляемые в body как есть, например, JSON | string

