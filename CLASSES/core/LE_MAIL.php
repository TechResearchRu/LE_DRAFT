<?php
/********************************************************************************
| LE MAIL v0.1.1 04.09.2021 by Pavel Belyaev, https://github.com/TechResearchRu |
| данный функционал требует тестирования, распространяется КАКЕСТЬ              |
********************************************************************************/

class LE_MAIL
{
    protected $eol="\r\n", $separator="qwe112233qwe", $charset="windows-1251";
    protected $sep;

    protected function encode($body)
    {
        return iconv('UTF-8', $this->charset.'//TRANSLIT', $body);
    }
    
    protected function base_encode($text)
    {
        $subject = base64_encode($this->encode($text));
        return '=?'.$this->charset.'?B?'.$text.'?=';
    }

    protected function headers($from,$type)
    {
        $eol = $this->eol;
        $sep = $this->separator;
        $headers ="MIME-Version: 1.0".$eol;
        $headers .='Content-Type: multipart/mixed; boundary="'.$sep.'"'.$eol;
        $headers .="From: ".$from.$eol;
        return $headers;
    }

    protected function attach_info($path)
    {
        $file = pathinfo($path)['basename'];
        $mime = mime_content_type($path);
        if ($mime===false) $mime="application/octet-stream";
        return [$file,$mime];     
    }
    
    protected function attachment($path=null)
    {
        if ($path===null || $path===false || !is_file($path)) return false;
        $eol = $this->eol;
        list ($filename,$mime) = $this->attach_info($path);
        $content = file_get_contents($path);
        $body = $this->sep.'Content-Type: '.$mime.'; name="'.$filename.'"'.$eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol;
        $body .= chunk_split(base64_encode($content)) . $eol;
        return $body;
    }

    protected function html_wrapper($text)
    {
        $html ='<html><head><META http-equiv="Content-Type" content="text/html; charset='.$this->charset.'"></head>';
        $html .='<body>'.$text.'</body></html>';
        return $html;
    }
    
    protected function html_message($text)
    { 
        $text = $this->encode($text);
        $text = $this->html_wrapper($text);
        $eol = $this->eol;
        $body = $this->sep.'Content-Type: text/html; charset="'.$this->charset.'"'.$eol; 
        $body .= "Content-Transfer-Encoding: Quot-Printed\n\n".$eol;
        $body .= $text . $eol;
        return $body;
    }
       
    public function send($from,$to,$message,$subject,$files=false)
    { 
        $eol = $this->eol;
        $this->separator = md5(time());
        $this->sep = "--" . $this->separator . $eol;

        $headers = $this->headers($from,($files!==null));
        $body = $this->html_message($message);
        if ($files!==false && is_string($files)) $files = [$files];
        if (is_array($files))
            foreach($files as $k=>$path) $body.=$this->attachment($path);
        
        $body .= "--" . $this->separator . "--";
        return mail($to, $subject, $body, $headers);
    }
}