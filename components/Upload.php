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

require Yii::$app->vendorPath . '/autoload.php';


class S3 extends Component
{


    public function downloadFile($bucket, $key){

    }


}