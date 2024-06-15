<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "person".
 *
 * @property int $id
 * @property int $user_id
 * @property string $full_name
 * @property string $passport
 * @property string $department
 * @property string $position
 * @property string $duties
 *
 * @property User $user
 */
class Person extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'full_name', 'passport', 'department', 'position', 'duties'], 'required'],
            [['user_id'], 'integer'],
            [['duties'], 'string'],
            [['full_name', 'passport', 'department', 'position'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'full_name' => 'Full Name',
            'passport' => 'Passport',
            'department' => 'Department',
            'position' => 'Position',
            'duties' => 'Duties',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
