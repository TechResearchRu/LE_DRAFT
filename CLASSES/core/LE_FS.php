<?php
/** @var \DB $db*/

if(!defined("I")) die;
//работа с файлами
class LE_FS 
{
    //+++добавить рекурсивное добавление родительских...
    public static function create_folder ($path,$permissions=0755)
    {
        if(!is_dir($path)) mkdir($path, $permissions);
    }

	public static function clear_folder($path) 
	{
		$path = rtrim($path,"/")."/";

        if (!is_dir($path) || !($handle = opendir($path))) return;
		
		while(false !== ($f = readdir($handle)))
        {
            if($f == "." || $f == "..") continue;

            $obj=$path.$f;
            if (is_file($obj)) 
                unlink($obj);
            elseif (is_dir($obj))
                LE_FS::clear_folder($obj);
                
        }
        closedir($handle); 
	}


	//рекурсивное копирование папки LE_FS::copy_folder($src, $dest);
	public static function copy_folder($src, $dest) {
    if (stripos($src,".DS_Store")>0) return false;
    if ( is_dir( $src ) ) {
       	if (!file_exists($dest)) mkdir($dest, 0777, true);

        $d = dir( $src );
        while ( false !== ( $entry = $d->read() ) ) {
            if ( $entry != '.' && $entry != '..' ) 
                LE_FS::copy_folder( "$src/$entry", "$dest/$entry");
        }
        $d->close();
    }
    elseif (!file_exists($dest)) 
    {
    copy($src, $dest);
    echo 'copy file <b>'.$src.'</b> to  <b>'.$dest."</b>\n<br>";
    }  
    
    }

    public static function dir_files($dir,$func=false)
    {
        if (!$dir||empty($dir)||!is_dir($dir)) return 1;
        if($func===false) return 2;
        
        $d = dir( $dir );
        while ( false !== ( $entry = $d->read() ) ) {
            if ( $entry == '.' || $entry == '..' ) continue; 
            $func($entry);
        }
        $d->close();

    }

    //LE_FS::save_from_post($inp=['f_name'=>,'path'=>])
    public static function SAVE_POST($inp,$debug=false)
    {
        $f_name = (isset($inp['f_name'])) ? $inp['f_name'] : 'file';
        
        if (!isset($inp['path']) || !is_dir($inp['path'])) return false;
        $inp['path'] = rtrim($inp['path']);

       // echo $inp['path'];
        
        if (!isset($_FILES[$f_name])) return false;

        $F = $_FILES[$f_name];

       // echo_arr($F);


        $SAVE_FILE = function($path,$index=false) use (&$F,&$debug)
        {   
            $f_inf=[];
            if ($index!==false)
            {
                if (!isset($F["tmp_name"][$index])) return false;
                $f_inf['tmp_name'] = $tmp_name = $F["tmp_name"][$index];
                 $f_inf['name'] = $file_name = $F["name"][$index];
                 $f_inf['type'] = $F["type"][$index];
                 $f_inf['size'] = $F["size"][$index];

            }
            else
            {
                if (!isset($F["tmp_name"])) return false;
                $f_inf['tmp_name'] = $tmp_name = $F["tmp_name"];
                $f_inf['name'] = $file_name = $F["name"];
                $f_inf['type'] = $F["type"];
                $f_inf['size'] = $F["size"];

            }

           // echo $tmp_name.BR;
           // echo $file_name.BR;

            if (!is_uploaded_file($tmp_name)) return false;

            $n = LE_FS::GEN_FNAME($file_name, $path);
            $out = $path.DS.$n;
            if (!move_uploaded_file($tmp_name, $out)) return false;

            if (!file_exists($out)) return false;

            if($debug!==false) $debug[$n] = $f_inf;

            return $n;


        };

        if (is_array($F['tmp_name']))
        {
            $cnt = count($F['tmp_name']);
            $res = [];
            for ($i=0;$i<$cnt;$i++)
            {
                $_fn = $SAVE_FILE($inp['path'],$i);
                if ($_fn!==false) $res[] = $_fn;
            }

        }
        else
        {
            return $SAVE_FILE($inp['path']);
        }

        $cnt = count($res);
        if (!$cnt>0) return false;
        if ($cnt===1) return $res[0];
        return $res;
   
    }


    public static function GEN_FNAME($inp_name = false, $path = false, $prefix=false) 
    {
        $ext = ($inp_name) ? '.'.pathinfo ($inp_name,PATHINFO_EXTENSION) : ''; //extension .jpg

        //file name alphabet
        $fn_alphabet = [0,1,2,3,4,5,6,7,8,9,'A','B','C','D',
        'E','F','G','H','I','J','K','L','M','N','O','P','Q',
        'R','S','T','U','V','W','X','Y','Z','_','-'
        ];

        $microtime = microtime(1);
        $create_time = 1540388275;
        $diff_time = Ceil($microtime*10000)-($create_time*10000);

        $new = int2alphabet($fn_alphabet,$diff_time);

        if ($prefix!==false)  $new = $prefix.$new;


        //проверка существования
        if ($path && is_dir($path))
        {
            $part = rtrim($path,DS);
            $i=1;
            while(is_file($path . DS . $new.$ext))
            {
                if ($i>100) exit("problem gen file name!!!");
                $new.=$fn_alphabet[rand(0,27)];
                $i++;
            }
        }
        return $new.$ext;
    }


    public static function Apply2Files($path,&$func,$recouse=0) 
    {
        $path = rtrim($path,"/\\").DS;

        if (!is_dir($path) || !($handle = opendir($path))) return;
        
        while(false !== ($f = readdir($handle)))
        {
            if($f == "." || $f == "..") continue;

            $obj=$path.$f;
            if (is_file($obj)) 
                $func($obj);
            elseif (is_dir($obj) && $recouse)
                LE_FS::Apply2Files($obj,$func,$recouse);
                
        }
        closedir($handle); 
    }

}
