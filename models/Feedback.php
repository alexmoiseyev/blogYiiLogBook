<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $username
 * @property string|null $email
 * @property string|null $message
 */
class Feedback extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'username', 'email', 'message'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['username', 'email', 'message'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'username' => 'Username',
            'email' => 'Email',
            'message' => 'Message',
        ];
    }
    public function saveFeedback($user){
        $this->user_id=$user->id;
        $this->username=$user->name;
        $this->email=$user->email;
        $this->save(false);
    }
}
