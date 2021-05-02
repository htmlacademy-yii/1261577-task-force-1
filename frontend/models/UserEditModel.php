<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class UserEditModel extends ActiveRecord
{
    public $name;
    public $email;
    public $city;
    public $birthday;
    public $about;
    public $categories;
    public $phone;
    public $skype;
    public $telegram;
    public $newPassword;

    public function attributeLabels()
    {
        return [
            'name' => 'ВАШЕ ИМЯ',
            'email' => 'EMAIL',
            'city' => 'ГОРОД',
            'birthday' => 'ДЕНЬ РОЖДЕНИЯ',
            'about' => 'ИНФОРМАЦИЯ О СЕБЕ',
            'phone' => 'ТЕЛЕФОН',
            'skype' => 'SKYPE',
            'telegram' => 'TELEGRAM',
            'newPassword' => 'НОВЫЙ ПАРОЛЬ',
            'categories' => 'Категории'
        ];
    }

    public function rules()
    {
        return [
            [['name','email','city','birthday','about','phone','skype','telegram', 'categories','newPassword'],'safe'],
//            ['newPassword','password'],
        ];
    }
}
