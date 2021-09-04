[< Documentation](../)
# LE_MAIL
&copy; by Pavel Belyaev, LE Framework, Tech-Research.ru

## About
Простейший класс для отправки почты механизмом sendmail, требует настроеного MTA (например, Postfix)

### Применение

#### Обычное письмо HTML
```php
$mail = new LE_MAIL;

$from="robot@example.org";
$to="mymail@example.org";
$subject="test123";
$body="<p>text of mail</p>";


$mail->send($from,$to,$body,$subject);
```
#### Письмо с вложением
```php
$mail = new LE_MAIL;

$from="robot@example.org";
$to="mymail@example.org";
$subject="test123";
$body="<p>text of mail</p>";


$files = [];
//пути до файлов
$files[] = __DIR__."/1.xls";
$files[] = __DIR__."/2.xls";


$mail->send($from,$to,$body,$subject,$files);
```