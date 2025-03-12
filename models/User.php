<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $password
 * @property int|null $isAdmin
 * @property string|null $photo
 * @property string|null $about
 *
 * @property Comment[] $comments
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isAdmin'], 'integer'],
            [['name', 'email', 'password', 'photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'isAdmin' => 'Is Admin',
            'photo' => 'Photo',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findIdentity($id){
        return User::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null){

    }
    public function validateAuthKey($authKey){

    }
    public function getId(){
        return $this->id;
    }
    public function getAuthKey(){

    }
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }

    public static function findByUserName($username){
        return User::find()->where(['name'=>$username])->one();
    }

    public function validatePassword($password){
        return ($this->password == md5($password))? true : false;
    }
    public function create()
    {
        return $this->save(false);
    }
    public function saveFromVk($uid, $name, $photo)
    {
        $user = User::findOne($uid);
        if($user)
        {
            return Yii::$app->user->login($user);
        }
        
        $this->id = $uid;
        $this->name = $name;
        $this->photo = $photo;
        $this->create();
        
        return Yii::$app->user->login($this);
    }

    public function saveImage($filename)
    {
        $this->image = $filename;
        return $this->save(false);
    }
    public function saveDescription($about)
    {
        $this->about = $about;
        return $this->save(false);
    }

    public function getImage()
    {
        return ($this->image) ? '/uploads/' . $this->image : '/uploads/no-image.png';
    }

    public function deleteImage()
    {
        $imageUploadModel = new ImageUpload();
        $imageUploadModel->deleteCurrentImage($this->image);
    }
}
