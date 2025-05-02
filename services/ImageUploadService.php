<?php
namespace app\services;

use yii\web\UploadedFile;
use app\models\ImageUpload;

use Yii;
class ImageUploadService
{
    public function upload(UploadedFile $file, string $oldImage = null)
    {
        $model = new ImageUpload();
        return $model->uploadFile($file, $oldImage);
        
    }
    
}