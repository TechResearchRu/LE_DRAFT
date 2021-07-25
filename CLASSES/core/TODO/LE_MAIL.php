<?php
/*
Mail v3.1
*/
if(!defined("I")) die;
//статический класс для отправки уведомлений
class LE_MAIL
{
    //отправка текстового письма Mail::send("электронный@адрес", "адрес отправителя", "текст письма", "тема_письма")
    public static function send($address,$sender,$mail_body,$subject)
    {	
			$headers="";
			$text ='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional //EN">
			<html><head>
			<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
			</head>
			<body>';
            $text.=$mail_body;
			$text.='</body></html>';
			
			$text=iconv('UTF-8', 'windows-1251//TRANSLIT', $text);
			
			$subject=iconv('UTF-8', 'windows-1251//TRANSLIT', $subject);
			$subject = '=?windows-1251?B?'.base64_encode($subject).'?=';
			
			$headers .= "MIME-Version: 1.0\n";
			$headers .="Content-Type: text/html; charset=windows-1251 \n";
			$headers .= "From: ".$sender."\r\n";
			
			mail($address, $subject, $text, $headers);
    }
   


    //отправка письма с вложением Mail::send("электронный@адрес", "адрес отправителя", "тема письма", "текст письма", "путь до файла", "имя файла(отображаемое в письме)")
    public static function send_file($address, $sender, $subject, $text, $path, $filename) 
  	{
      $text=iconv('UTF-8', 'windows-1251//TRANSLIT', $text);
      $subject=iconv('UTF-8', 'windows-1251//TRANSLIT', $subject);
      $subject = '=?windows-1251?B?'.base64_encode($subject).'?='; 
      $fp = fopen($path,"r");	if (!$fp) {print "Файл $path не может быть прочитан"; exit();} 
      $file = fread($fp, filesize($path)); fclose($fp); 
      $boundary = "--".md5(uniqid(time())); // генерируем разделитель 
      $headers .= "MIME-Version: 1.0\n"; 
      $headers .="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
      $headers .= "From: ".$sender."\r\n"; 
      $multipart .= "--$boundary\n"; 
      $multipart .= "Content-Type: text/html; charset=windows-1251\n"; 
      $multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n"; 
      $multipart .= "$text\n\n"; 

      $message_part = "--$boundary\n"; 
      $message_part .= "Content-Type: application/octet-stream\n"; 
      $message_part .= "Content-Transfer-Encoding: base64\n"; 
      $message_part .= "Content-Disposition: attachment; filename = \"".$filename."\"\n\n"; 
      $message_part .= chunk_split(base64_encode($file))."\n"; 
      $multipart .= $message_part."--$boundary--\n"; 
      if(!mail($address, $subject, $multipart, $headers)) {echo "К сожалению, письмо не отправлено"; exit();} 
    }
     
     
    public function gen_message($mailfrom,$mailto,$body,$subject,$file=false)
    { 
        $separator = md5(time());
        
        $filename = 'myfile';
        $path = 'your path goes here';
        $file = $path . "/" . $filename;

        $mailto = 'mail@mail.com';
        $subject = 'Subject';
        $message = 'My message';

        $content = file_get_contents($file);
        $content = chunk_split(base64_encode($content));

        // a random hash will be necessary to send mixed content
        

        // carriage return type (RFC)
        $eol = "\r\n";

        // main header (multipart mandatory)
        $headers = "From: name <test@test.com>" . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        $headers .= "This is a MIME encoded message." . $eol;

        // message
        $body = "--" . $separator . $eol;
        $body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol;
        $body .= $message . $eol;

        // attachment
        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol;
        $body .= $content . $eol;
        $body .= "--" . $separator . "--";

        //SEND Mail
        if (mail($mailto, $subject, $body, $headers)) {
            echo "mail send ... OK"; // or use booleans here
        } else {
            echo "mail send ... ERROR!";
            print_r( error_get_last() );
        }
    }





}