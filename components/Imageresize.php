<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Url;
use app\components\Customs3;

class Imageresize extends Component
{
    private $file = '';
    private $width = 200;
    private $height = 200;
    private $method = 'resize';
    private $mediaFile = '/web/media';
    private $quality = 70;
    private $s3;
    private $post_sizes = [
        ['width' => '150', 'height' => '100', 'method' => 'crop'],
        ['width' => '1000', 'height' => '1000', 'method' => 'resize'],
        ['width' => '300', 'height' => '250', 'method' => 'crop'],
        ['width' => '400', 'height' => '230', 'method' => 'crop'],
        ['width' => '300', 'height' => '290', 'method' => 'crop'],
        ['width' => '500', 'height' => '350', 'method' => 'resize'],
        ['width' => '500', 'height' => '350', 'method' => 'crop'],
        ['width' => '900', 'height' => '430', 'method' => 'crop'],
    ];
    private $user_sizes = [
        ['width' => '80', 'height' => '80', 'method' => 'crop'],
        ['width' => '300', 'height' => '300', 'method' => 'crop'],
        ['width' => '300', 'height' => '250', 'method' => 'crop'],
    ];

    public function __construct($config = array())
    {
        $this->s3 = Yii::$app->customs3;
        $this->mediaFile = Yii::$app->basePath . $this->mediaFile;
    }

    public function thump($file, $width, $height, $method)
    {
        if ($json = $this->isJson($file))
            $file = $json->image;

        if (empty($this->file) || $this->file === 0)
            $this->file = 'other/no-profile-image.jpg';

        $filethump = $this->thumpName($width, $height, $method);
        $fileExtract = explode('/', $file);

        return 'https://dw4xox9sj1rhd.cloudfront.net/' . $fileExtract[0] . '/' . $filethump . '/' . $fileExtract[1];
    }

    public function isJson($string)
    {
        $json = json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE) ? $json : false;
    }


    public function s3Resize($path, $width, $height, $method)
    {
        $filethump = $this->thumpName($width, $height, $method);
        $dir = dirname($path);
        $thumppath = $dir . '/' . $filethump;
        $key_folder = basename($dir);
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (!is_dir($thumppath)) {
            mkdir($thumppath, 0777, true);
        }
        $file_name = basename($path);
        $thumppath = $thumppath . '/' . $file_name;

        $im = new \Imagick($path);
        $im->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
        $im->setImageFormat($ext);
        $im->setImageCompressionQuality($this->quality);
        $im->stripImage();

        if ($method == 'resize') {
            $im->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 0.9, true);
            $im->writeImage($thumppath);
        } else {
            if ($im->getimageheight() > $im->getimagewidth() || (($width + $height) > ($im->getimagewidth() + $im->getimageheight()))) {
                $im = new \Imagick($path);
                $im->scaleimage(0, $height);
                $im->writeimage($thumppath);
                $bkim = new \Imagick($path);
                $bkim->evaluateImage(\Imagick::EVALUATE_MULTIPLY, 0.3, \Imagick::CHANNEL_ALPHA);
                $bkim->cropThumbnailImage($width, $height);
                $bkim->blurimage(25, 15);
                $bkim->setImageFormat('png');
                $bkim->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
                $im->resizeImage($width / 1.5, $height * 2, \Imagick::FILTER_LANCZOS, 0.9, true);
                $shadow = $im->clone();
                $shadow->setImageBackgroundColor(new \ImagickPixel('black'));
                $shadow->shadowImage(70, 10, 15, 15);
                $bkim->compositeImage($shadow, \Imagick::COMPOSITE_OVER, (((($bkim->getImageWidth()) - ($im->getImageWidth()))) / 2), (((($bkim->getImageHeight()) - ($im->getImageHeight()))) / 2));
                $bkim->compositeImage($im, \Imagick::COMPOSITE_DEFAULT, (((($bkim->getImageWidth()) - ($im->getImageWidth()))) / 2), (((($bkim->getImageHeight()) - ($im->getImageHeight()))) / 2));
                @$bkim->writeImage($thumppath);
            } else {
                $im->cropThumbnailImage($width, $height);
                $im->writeImage($thumppath);
            }
        }
        $im->clear();
        $im->destroy();

        $this->s3->uploadFromPath($thumppath, 'hangshare.media', "{$key_folder}/{$filethump}/{$file_name}");
        @unlink($thumppath);
        return $this;
    }

    public function PatchResize($bucket, $key, $type = 'post')
    {
        $path = $this->s3->downloadFile();

        $array = "{$type}_sizes";
        foreach ($this->$array as $size) {
            $this->s3Resize($path, $size['width'], $size['height'], $size['method']);
        }
        @unlink($path);
        return true;

    }

    protected function thumpName($width, $height, $method)
    {
        return $width . 'x' . $height . '-' . $method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
}
