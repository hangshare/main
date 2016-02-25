<?php

namespace app\components;

use Yii;
use yii\base\Component;

class Imageresize extends Component
{

    private $file = '';
    private $width = 200;
    private $height = 200;
    private $method = 'resize';
    private $mediaFile = '/web/media';
    private $quality = 100;

    public function __construct($config = array())
    {
        $this->mediaFile = Yii::$app->basePath . $this->mediaFile;
    }

    public function thump($file, $width, $height, $method)
    {
        $this->file = $file;
        $this->width = $width;
        $this->height = $height;
        $this->method = $method;
        if (empty($this->file) || $this->file === 0 || !is_file($this->mediaFile . '/' . $this->file)) {
            $this->file = 'other/no-profile-image.jpg';
        }
        try {
            return @$this->resize();
        } catch (Exception $ex) {

        }
    }

    protected function resize()
    {
        $filethump = $this->width . 'x' . $this->height . '-' . $this->method;
        $fileExtract = explode('/', $this->file);
        $path_info = pathinfo($this->file);
        $ext = $path_info['extension']; // "bill"
        $path = Yii::$app->basePath . '/web/media/' . $fileExtract[0] . '/' . $filethump;
        $thumppath = $path . '/' . $fileExtract[1];
//        @unlink($thumppath);
        if (!is_file($thumppath)) {
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $im = new \Imagick($this->mediaFile . '/' . $this->file);
            $im->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $im->setImageFormat($ext);
            $im->setImageCompressionQuality($this->quality);

            if ($this->method == 'resize') {
                $im->resizeImage($this->width, $this->height, \Imagick::FILTER_LANCZOS, 0.9, true);
                $im->writeImage($thumppath);
            } else {
                if ($im->getimageheight() > $im->getimagewidth() || (($this->width + $this->height) > ($im->getimagewidth() + $im->getimageheight()))) {
                    $im = new \Imagick($this->mediaFile . '/' . $this->file);
                    $im->scaleimage(0, $this->height);
                    $im->writeimage($thumppath);

                    $bkim = new \Imagick($this->mediaFile . '/' . $this->file);
                    $bkim->evaluateImage(\Imagick::EVALUATE_MULTIPLY, 0.3, \Imagick::CHANNEL_ALPHA);
                    $bkim->cropThumbnailImage($this->width, $this->height);
                    $bkim->blurimage(25, 15);
                    $bkim->setImageFormat('png');
                    $bkim->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);

                    //$im->cropThumbnailImage($this->width / 2, $this->height + $this->height / 3);
                    $im->resizeImage($this->width / 2, $this->height / 2, \Imagick::FILTER_LANCZOS, 0.9, true);
                    $shadow = $im->clone();
                    $shadow->setImageBackgroundColor(new \ImagickPixel('black'));
                    $shadow->shadowImage(70, 10, 15, 15);
                    $bkim->compositeImage($shadow, \Imagick::COMPOSITE_OVER, (((($bkim->getImageWidth()) - ($im->getImageWidth()))) / 2), (((($bkim->getImageHeight()) - ($im->getImageHeight()))) / 2));
                    $bkim->compositeImage($im, \Imagick::COMPOSITE_DEFAULT, (((($bkim->getImageWidth()) - ($im->getImageWidth()))) / 2), (((($bkim->getImageHeight()) - ($im->getImageHeight()))) / 2));
                    @$bkim->writeImage($thumppath);
                } else {
                    $im->cropThumbnailImage($this->width, $this->height);
                    $im->writeImage($thumppath);
                }
            }
            $im->clear();
            $im->destroy();
        }
        //Url::home(true)
        return 'https://s3-eu-west-1.amazonaws.com/hangshare.media/' . $fileExtract[0] . '/' . $filethump . '/' . $fileExtract[1];
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
