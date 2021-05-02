<?php

namespace taskForce\user\infrastructure\builder;

use frontend\models\User as modelUser;
use taskForce\category\domain\CategoriesList;
use taskForce\category\infrastructure\builder\ArCategoryBuilder;
use taskForce\user\domain\Contact;
use taskForce\user\domain\Detail;
use taskForce\user\domain\User;

class ArUserBuilder
{
    /**
     * @param modelUser $model
     * @param bool $detailView
     * @return User
     */
    public function build(modelUser $model = null, $detailView = false): User
    {
        if($model === null) {
            $model = new modelUser();
        }
        $user = new User();
        $categoryBuilder = new ArCategoryBuilder();
        $categoriesList = new CategoriesList();
        $contacts = new Contact();
        foreach ($model->categories as $category) {
            $categoriesList[] = $categoryBuilder->build($category);
        }
        $user->categories = $categoriesList;
        $user->id = $model->id;
        $user->avatar = $model->avatar;
        $user->name = $model->name;
        $user->dateCreate = $model->created_at;
        $user->lastActivity = $model->last_activity;
        $user->rating = $model->rating;
        $detail = new Detail($model->about,$model->address,$model->birthday);
        $user->detail = $detail;
        $user->cityId = $model->city_id;
        if($detailView === true) {
            $contacts->email = $model->email;
            $contacts->phone = $model->phone;
            $contacts->skype = $model->skype;
            $contacts->telegram = $model->telegram;
        }
        $user->contacts = $contacts;
        $user->email = $model->email;
        $user->birthday = $model->birthday;

        return $user;
    }
}
