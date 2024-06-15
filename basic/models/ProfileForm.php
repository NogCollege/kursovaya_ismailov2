<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Person;

/**
 * Profile form
 */
class ProfileForm extends Model
{
    public $full_name;
    public $passport;
    public $department;
    public $position;
    public $duties;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'passport', 'department', 'position', 'duties'], 'required'],
            [['full_name', 'passport', 'department', 'position'], 'string', 'max' => 255],
            [['duties'], 'string'],
        ];
    }

    /**
     * Populates the form with existing data.
     *
     * @param Person $person
     */
    public function loadFromPerson($person)
    {
        $this->full_name = $person->full_name;
        $this->passport = $person->passport;
        $this->department = $person->department;
        $this->position = $person->position;
        $this->duties = $person->duties;
    }

    /**
     * Saves the form data to the given Person model.
     *
     * @param Person $person
     * @return bool whether the saving succeeded
     */
    public function saveToPerson($person)
    {
        if (!$this->validate()) {
            return false;
        }

        $person->full_name = $this->full_name;
        $person->passport = $this->passport;
        $person->department = $this->department;
        $person->position = $this->position;
        $person->duties = $this->duties;

        return $person->save();
    }
}
