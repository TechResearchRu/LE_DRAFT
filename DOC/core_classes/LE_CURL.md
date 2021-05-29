# LE_CURL
&copy; by Pavel Belyaev

## About
Данный класс создан для выполнения http запросов, удобно для работы с RestApi, для скачивания любого контента по сети итд...

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

### Выполнение запроса
#### Простой запрос GET

```php
$params = ['url'=>'https://example.com/index.php']

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
]

$CL->query($params);

$url = $CL->redirect;

```


