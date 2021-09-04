<?php
/*
Mail v3.1
*/
if(!defined("I")) die;
//статический класс для отправки уведомлений
class LE_MAIL
{
/*
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
*/
    public $eol="\r\n";

    public function encode($body)
    {
        return iconv('UTF-8', 'windows-1251//TRANSLIT', $body);
    }
    
    
    public function subject($subject)
    {
        $subject = base64_encode($this->encode($subject));
        return '=?windows-1251?B?'.$subject.'?=';
    }

    //type=0 - html, 1 - multipath/mixed
    public function headers($from,$type,$boundary)
    {
        $eol = $this->eol;
        $sep = $this->separator;
        //1
        $headers = "";
        $headers .="MIME-Version: 1.0".$eol;
        
        //if ($type)
            $headers .="Content-Type: multipart/mixed; boundary=\"$sep\"\n";
        //else 
        //    $headers .="Content-Type: text/html; charset=windows-1251".$eol;
        
        $headers .="From: ".$from.$eol;
       
    
        //$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        //$headers .= "This is a MIME encoded message." . $eol;

        return $headers;

    }
    public $separator="qwe112233qwe";

    public function attach_info($path)
    {
        $path_parts = pathinfo($path);
        $filename = is_set($res['filename']) ? $res['filename'] : '';
        $file_ext = is_set($res['extension']) ? ".".$res['extension'] : '';
        $file = $filename.$file_ext;
        $mime = mime_content_type($path);
        if ($mime===false) $mime="application/octet-stream";
        return [$file,$mime];
        
    }
    
    public function attachment($path=null)
    {
        if ($path===null || $path===false || !is_file($path)) return false;
        $eol = $this->eol;
        $sep = $this->separator;

        list ($file,$mime) = $this->attach_info($path)

        $content = file_get_contents($path);
        $content = chunk_split(base64_encode($content));
        $body .= "--" . $sep . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol;
        $body .= $content . $eol;
        return $body;
    }

    public function html_wrapper($text)
    {
        $html ='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional //EN">';
        $html .='<html><head><META http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>';
        $html .='<body>'.$text.'</body></html>';

        return $html;
    }
    
    public function html_message($text)
    { 
        $text = $this->encode($text);
        $text = $this->html_wrapper($text);
        $eol = $this->eol;
        $sep = $this->separator;
        $body = "--" . $sep . $eol;
        $body .= 'Content-Type: text/html; charset="windows-1251"'.$eol; 
        $body .= "Content-Transfer-Encoding: Quot-Printed\n\n".$eol; 
        //$body .= "Content-Transfer-Encoding: 8bit" . $eol;
        $body .= $text . $eol;
        return $body;
    }
     
     
    public function gen_message($mailfrom,$mailto,$body,$subject,$files=false)
    { 
        $this->separator = md5(time());
        $eol = $this->eol;

        // main header (multipart mandatory)
        $headers = $this->headers($from,($file!==null),$boundary);

        // message
        $body = $this->html_message($message);
        

        if ($files!==false && is_string($files)) $files = [$files];
        if (is_array($files))
        {
            foreach($files as $k=>$path)
            {
                $body.=$this->attachment($path);
            }
        }
        
        $body .= "--" . $this->separator . "--";

        //SEND Mail
        return mail($mailto, $subject, $body, $headers);
        
    }





}