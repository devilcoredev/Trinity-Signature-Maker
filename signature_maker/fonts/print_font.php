<?php
    define("ARMOY_INCLUDED", true);
    include("/../config.php");
    if(GDVersion() && isset($_GET["id_font"]) && $_GET["id_font"] != '')
    {
        $id_font = strtolower($_GET["id_font"]);
        if(isset($fonts["$id_font"]["text"]) && $fonts["$id_font"]["text"] != '')
        {
            header("Content-type: image/png");

            $font = $fonts["$id_font"];
            $dim_x = $font['x'];
            $dim_y = $font['y'];

            $im = imageCreateFromVersion($dim_x, $dim_y);

            $col = imagecolorallocate($im, 255, 255, 25);
            imagefilledrectangle($im, 0, 0, $dim_x, $dim_y, $col);
            imagecolordeallocate($im, $col);

            $box = imagettfbbox(30, 0, $font["name"], $font["text"]);
            $black = imagecolorallocate($im, 0, 0, 0);
            imagettftext($im, 30, 0, ($dim_x - $box[2]) / 2, 40, $black, $font["name"], $font["text"]);
            imagecolordeallocate($im, $black);

            imagepng($im);
            imagedestroy($im);
        }
    }
?>