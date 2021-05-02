<?php

namespace taskForce\user\domain;

use DateTime;
use taskForce\category\domain\CategoriesList;
use taskForce\share\StringHelper;
use yii\validators\DateValidator;

class User
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $avatar;

    /**
     * @var string
     */
    public $name;

    /**
     * @var DateTime
     */
    public $dateCreate;

    /**
     * @var Contact
     */
    public $contacts;

    /**
     * @var DateTime
     */
    public $lastActivity;

    /**
     * @var double
     */
    public $rating;

    /**
     * @var CategoriesList
     */
    public $categories;

    /**
     * @var Detail
     */
    public $detail;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int
     */
    public $cityId;

    /**
     * @var string
     */
    public $email;

    /**
     * @var DateTime
     */
    public $birthday;

    public function toArray()
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'name' => $this->name,
            'registrationPast' => StringHelper::getRegistrationPastTime($this->dateCreate),
            'contacts' => $this->contacts->toArray(),
            'pastTime' => StringHelper::getPastActivityTime($this->lastActivity),
            'rating' => $this->rating,
            'categories' => $this->categories->toArray(),
            'detail' => $this->detail->toArray(),
            'city_id' => $this->cityId,
            'email' => $this->email,
            'birthday' => date('d.m.Y',strtotime($this->birthday)),
        ];
    }

    /**
     * @param string $pass
     */
    public function setPassword(string $pass)
    {
        $this->password = $pass;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
