<?php
namespace app\services;

use yii\web\UploadedFile;
use app\models\ImageUpload;

class ImageUploadService
{
    public function upload(UploadedFile $file, string $oldImage = null): string
    {
        $model = new ImageUpload();
        return $model->uploadFile($file, $oldImage);
    }
}