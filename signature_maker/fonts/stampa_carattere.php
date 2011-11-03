<?php
    include("/../config.php");
    function isGD2supported()
    {
        global $GD2;
        if(isset($GD2) && $GD2)
            return $GD2;
        else
        {
            $php_ver_arr = explode('.', phpversion());
            $php_ver = intval($php_ver_arr[0])*100 + intval($php_ver_arr[1]);

            if($php_ver < 402) // PHP <= 4.1.x
                $GD2 = in_array("imagegd2", get_extension_funcs("gd"));
            else if($php_ver < 403) // PHP = 4.2.x
            {
                $im = imagecreatetruecolor(10, 10);
                if($im)
                {
                    $GD2 = 1;
                    imagedestroy($im);
                }else $GD2 = 0;
            }else $GD2 = function_exists("imagecreatetruecolor"); // PHP = 4.3.x
        }

        return $GD2;
    }

    function GDVersion()
    {
        if(!in_array("gd", get_loaded_extensions()))
            return 0;
        else if(isGD2supported())
            return 2;
        else return 1;
    }

    function IsFormatSupported($format)
    {
        if(($format=="gif") && (imagetypes() & IMG_GIF))
            return true;
        else if(($format=="jpeg") && (imagetypes() & IMG_JPG))
            return true;
        else if(($format=="png") && (imagetypes() & IMG_PNG))
            return true;
        else return false;
    }
?>
<?php
    if(GDVersion() && isset($_GET["id_font"]) && $_GET["id_font"]!='')
    {
        $id_font = $_GET["id_font"];
        if(isset($fonts["$id_font"]["text"]) && $fonts["$id_font"]["text"]!='')
        {
            header("Content-type: image/png");

            $font = $fonts["$id_font"];
            $dim_x = $font['x'];
            $dim_y = $font['y'];

            if(GDVersion() == 1)
                $im = imagecreate($dim_x, $dim_y);
            else $im = imagecreatetruecolor($dim_x, $dim_y);

            $col = imagecolorallocate($im, 255, 255, 25);
            imagefilledrectangle($im, 0, 0, $dim_x, $dim_y, $col);
            imagecolordeallocate($im, $col);

            $box = imagettfbbox(30, 0, $font["name"], $font["text"]);
            $black = imagecolorallocate($im, 0, 0, 0);
            imagettftext($im, 30, 0, ($dim_x - $box[2])/2, 40, $black, $font["name"], $font["text"]);
            imagecolordeallocate($im, $black);

            imagepng($im);
            imagedestroy($im);
        }
    }
?>