<?php
namespace Jazor\QRCode;

class QRImage {

    public static $Width = 0;
    public static $Height = 0;
    public static $Size = 0;
    public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4,$saveandprint=FALSE)
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame);

        if ($filename === false) {
            header("Content-type: image/png");
            imagepng($image);
        } else {
            if ($saveandprint === TRUE) {
                imagepng($image, $filename);
                header("Content-type: image/png");
                imagepng($image);
            } else {
                imagepng($image, $filename);
            }
        }

        imagedestroy($image);
    }

    public static function raw($frame, $type = 'png', $pixelPerPoint = 8, $outerFrame = 4, $q = 85)
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame);
        ob_start();
        if ($type == 'jpeg') {
            imagejpeg($image, null, $q);
        } else {
            imagepng($image);
        }

        ImageDestroy($image);
        return ob_get_clean();
    }

    public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $q = 85)
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame);

        if ($filename === false) {
            Header("Content-type: image/jpeg");
            ImageJpeg($image, null, $q);
        } else {
            ImageJpeg($image, $filename, $q);
        }

        ImageDestroy($image);
    }

    public static function image($frame, $pixelPerPoint = 4, $outerFrame = 4)
    {
        $h = count($frame);
        $w = strlen($frame[0]);
        self::$Width = $w;
        self::$Height = $h;
        $imgW = $w + 2*$outerFrame;
        $imgH = $h + 2*$outerFrame;

        $base_image =ImageCreate($imgW, $imgH);

        $col[0] = ImageColorAllocate($base_image,255,255,255);
        $col[1] = ImageColorAllocate($base_image,0,0,0);

        imagefill($base_image, 0, 0, $col[0]);

        for($y=0; $y<$h; $y++) {
            for($x=0; $x<$w; $x++) {
                if ($frame[$y][$x] == '1') {
                    ImageSetPixel($base_image,$x+$outerFrame,$y+$outerFrame,$col[1]);
                }
            }
        }
        self::$Size = $imgW * $pixelPerPoint;

        $target_image =ImageCreate($imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
        ImageCopyResized($target_image, $base_image, 0, 0, 0, 0, $imgW * $pixelPerPoint, $imgH * $pixelPerPoint, $imgW, $imgH);
        ImageDestroy($base_image);

        return $target_image;
    }
}

