<?php
/**
 * Created by PhpStorm.
 * User: kaldmax
 * Date: 4/4/2016
 * Time: 4:06 PM
 */
namespace app\components;

use Aws\S3\S3Client;

use Yii;
use yii\base\Component;


require Yii::$app->vendorPath . '/autoload.php';

class Customs3 extends Component
{
    private $__accessKey = 'AKIAJ3JZA2TENDIDQTBQ';
    private $__secretKey = '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';
    private $__client;


    public function init()
    {
        parent::init();
        $this->__client = S3Client::factory(array(
            'credentials' => array(
                'key' => $this->__accessKey,
                'secret' => $this->__secretKey,
            ),
            'region' => 'eu-west-1',
            'version' => 'latest'
        ));
    }


    function mime_content_type($filename)
    {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            'epub' => 'application/epub+zip',
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        $ext = strtolower(array_pop(explode('.', $filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }

    public function downloadFile($bucket, $key)
    {
        $path = Yii::$app->basePath . '/s3tmp/' . $key;
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->__client->getObject(array(
            'Bucket' => $bucket,
            'Key' => $key,
            'SaveAs' => $path
        ));
        return $path;
    }

    public function uploadFromPath($path, $bucket, $key, $header = false)
    {
        if (!$header) {
            $header = [
                "Cache-Control" => "max-age=11536000",
                'Content-Type' => $this->mime_content_type($path),
                'uuid' => '14365123651274'
            ];
        }

        $this->__client->putObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'SourceFile' => $path,
            'Metadata' => $header,
            'ACL' => 'public-read',
            'CacheControl' => 'max-age=72800',
            'ContentType' => $this->mime_content_type($path)
        ]);
        @unlink($path);
        return $this;
    }


}