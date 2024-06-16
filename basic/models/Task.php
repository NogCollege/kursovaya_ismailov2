<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Task extends ActiveRecord
{
    const STATUS_PENDING = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_COMPLETED = 3;

    public static function tableName()
    {
        return 'task';
    }

    public function rules()
    {
        return [
            [['title', 'description', 'due_date', 'user_id', 'department_id', 'status'], 'required'],
            [['description', 'comment'], 'string'],
            [['due_date'], 'safe'],
            [['user_id', 'department_id', 'status'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'description' => 'Описание',
            'due_date' => 'Срок выполнения',
            'status' => 'Статус',
            'comment' => 'Комментарий',
            'user_id' => 'Исполнитель',
            'department_id' => 'Отдел',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    public static function getStatusLabels()
    {
        return [
            self::STATUS_PENDING => 'В ожидании',
            self::STATUS_IN_PROGRESS => 'В процессе',
            self::STATUS_COMPLETED => 'Завершена',
        ];
    }
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'assigned_to']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['id' => 'department_id']);
    }

}
