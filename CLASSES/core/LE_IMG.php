<?php
/**
 *  Author: Pavel Belyaev
 *  GitHub: https://github.com/TechResearchRu/LE_DRAFT
 *  Email: pavelbbb@gmail.com
 *  LE FRAMEWORK, LE_IMG v0.1 2021, need GD module in PHP
 */

class LE_IMG
{

    public static function LOAD($p)
    {
        if (!file_exists($p) || !is_file($p)) return false;

        list($w,$h,$t) = getimagesize($p);

        switch ($t)
        {
            case 1:$obj = imagecreatefromgif($p);break;
            case 2:$obj = imagecreatefromjpeg($p);break;
            case 3:$obj = imagecreatefrompng($p);break;
            default: return false; break;
        }

        return [$w,$h,$t,&$obj];
    }

    public static function SAVE (&$o,$t,$p)
    {
        switch ( $t ) 
        {
            case 1: imagegif($o,$p); break;
            case 2: imagejpeg($o,$p); break;
            case 3: imagepng($o,$p); break;
        } 
        imageDestroy($o);
        unset($o);
        return ;
    }


    //list($_w,$_h) =LE_IMG::calc_prop($w,$h,$w_out,$h_out,$maximize)
    public static function calc_prop($w,$h,$w_out,$h_out,$max=0)
    {
        $r = $h/$w; //отношение высоты к ширине

        if ($max && ($w<$w_out||$h<$h_out))
        {
            if (($w_out>0) && ($w<$w_out) && ($w=$w_out)) $h = Ceil($w*$r);
            if (($h_out>0) && ($h<$h_out) && ($h=$h_out)) $w = Ceil($h*pow($r,-1));
        }

        //если ширина не вписалась, уменьшаем ширину, а высоту пропорционально
        if (($w_out>0) && ($w>$w_out)) 
        {
            $w=$w_out;
            $h = Ceil($w*$r);
        }
        //если высота не вписалась, уменьшаем высоту, а ширину пропорционально
        if (($h_out>0) && ($h>$h_out)) 
        {
            $h=$h_out;
            $w = Ceil($h*pow($r,-1));
        }
        return [$w,$h]; //x,y
    }

    public static function calc_prop2($w,$h,$w_out,$h_out)
    {
        $_w = $w/$w_out;
        $_h = $h/$h_out;
        $r = $h/$w;

        if($_w<$_h)
        {
            $w = $w_out;
            $h = Ceil($w*$r);
        }
        else
        {
            $h=$h_out;
            $w = Ceil($h*pow($r,-1));
        }

        return [$w,$h];

    }



    //LE_IMG::resize ($in,$out,$mw=0,$mh=0);
    public static function resize ($in,$out,$mw=0,$mh=0)
    {
        if (!file_exists($in)) return false;
        $__load = LE_IMG::load($in);
        if ($__load===false) return false;
        list($w,$h,$t,$obj) = $__load;
        $nw=$w; $nh=$h;
        if (($mw>0) && ($w>$mw) && ($nw=$mw)) $nh = Ceil($nw*$h/$w);
        if (($mh>0) && ($nh>$mh) && ($nh=$mh)) $nw = Ceil($nh*$w/$h);
        $resource = imagecreatetruecolor ($nw,$nh); //создаем лист по новым размерам
        imagealphablending($resource, false);
        imagecopyresampled ($resource,$obj,0,0,0,0,$nw,$nh,$w,$h);
        imageSaveAlpha($resource, true);
        LE_IMG::save($resource,$t,$out);
        return true;
    }

    public static function resize_crop ($in,$out,$mw=0,$mh=0)
    {
        if (!file_exists($in)) return false;
        $__load = LE_IMG::load($in);
        if ($__load===false) return false;

        list($w,$h,$t,$obj) = $__load;

        list($nw,$nh) = LE_IMG::calc_prop2($w,$h,$mw,$mh);


        //resize      
        $resource = imagecreatetruecolor ($nw,$nh); //создаем лист по новым размерам
        imagealphablending($resource, false);
        imagecopyresampled ($resource,$obj,0,0,0,0,$nw,$nh,$w,$h);
        imageSaveAlpha($resource, true);

        //crop
        $cr = array('width'=>$mw,'height'=>$mh);
        $cr['x'] = Ceil(($nw-$mw)/2);
        $cr['y'] = Ceil(($nh-$mh)/2);
        $resource = imagecrop($resource,$cr);

        LE_IMG::save($resource,$t,$out);
        return ;
    }


    //срезка кусочков изображения со всех сторон
    public static function crop ($in,$out,$l=0,$t=0,$r=0,$b=0)
    {
    /*открытие*/
    list($w,$h,$t,$obj) = LE_IMG::load($in);

    /*новые размеры*/
    $n_w=$w-$l-$r; $n_h=$h-$t-$b;

    $res = imagecreatetruecolor($n_w,$n_h);
    imagealphablending($res,false);
    imagecopy ($res,$obj,0,0,$l,$t,$n_w,$n_h);
    imageSaveAlpha($res, true);

    LE_IMG::save($res,$t,$out);
    }   

    public static function fix_color($w,$h,&$img)
    {
        //fix for png not truecolor
        $uig = imagecreatetruecolor($w, $h);
        imageSaveAlpha($uig, true);
        //$transparent = imagecolortransparent ($img);
        $transparent = imagecolorallocatealpha($uig,255,255,255,127);
        imagefill($uig, 0, 0, $transparent);
        imagecopy($uig, $img, 0, 0, 0, 0, $w, $h);
        return $uig; 
    }



    public static function watermark1($url,$conf)
    {
        //return LE_IMG::demo_water($url,$conf);
        if (!is_file($url)) exit('Нет файла для наложения watermark');
        $__load = LE_IMG::load($url);
        if ($__load===false) return false;

        list($w,$h,$t,$src_img) = $__load;

        //fix for png not truecolor
        if (imageistruecolor($src_img)===false)
        {
            $uig = LE_IMG::fix_color($w,$h,$src_img);
            imagedestroy($src_img);
        }
        else
            $uig = &$src_img;

        $txt_w = $w*0.9; $txt_h = $h*0.9;
        if (!isset($conf['rgb'])) $conf['rgb'] = '78,78,78,40';      
        $col = explode(',',$conf['rgb']);
        $text = " ".$conf['text']." ";
        $angle =  -rad2deg(atan2((-$h),($w)));
        
        $color = imagecolorclosestalpha ($uig, $col[0], $col[1], $col[2], $col[3]);
        $size = (($txt_w+$txt_h)/2)*2/mb_strlen(" ".$text." ");

        $fnt = $conf['font'];
        $box  = imagettfbbox ( $size, $angle, $fnt, $text);
        $pad_left = ($txt_w/2 - abs($box[4] - $box[0])/2);
        $pad_top = ($txt_h/2 + abs($box[5] - $box[1])/2);
        
        imagettftext($uig,$size ,$angle, $pad_left,$pad_top , $color, $fnt, $text);

        LE_IMG::save($uig,$t,$url);
        return;
    }

    public static function watermark2($url,$stamp_path,$skeep_err=1)
    {
        if (!is_file($url)) 
        {
        if ($skeep_err) return false;
        exit('Нет файла для наложения watermark');
        }
        
        $__load = LE_IMG::load($url);
        if ($__load===false) return false;

        list($w,$h,$t,$src_img) = $__load;

        //fix for png not truecolor
        if (imageistruecolor($src_img)===false)
        {
            $uig = LE_IMG::fix_color($w,$h,$src_img);
            imagedestroy($src_img);
        }
        else
            $uig = &$src_img;

        $_stamp = LE_IMG::load($stamp_path);

        if ($_stamp===false) return false;

        list($st_w,$st_h,$st_t,$stamp) = $_stamp;


        list($_w,$_h) = LE_IMG::calc_prop($st_w,$st_h,$w,$h,1);

        $dst_x = $dst_y =0;
        if ($_w<$w) $dst_x = ceil(($w-$_w)/2);
        if ($_h<$h) $dst_y = ceil(($h-$_h)/2);

        imagecopyresampled ($uig,$stamp,$dst_x, $dst_y,0,0,$_w,$_h,$st_w,$st_h);

        LE_IMG::save($uig,$t,$url);
        imagedestroy($stamp);
        return;
    }
}