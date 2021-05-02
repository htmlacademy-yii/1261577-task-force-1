<?php

namespace frontend\controllers;

use frontend\models\UserEditModel;
use taskForce\category\application\ManagerCategory;
use taskForce\city\application\ManagerCity;
use taskForce\user\application\ManagerUser;

class AccountController extends SecuredController
{
    /**
     * @var ManagerUser
     */
    private $managerUser;

    /**
     * @var ManagerCity
     */
    private $managerCity;

    /**
     * @var ManagerCategory
     */
    private $managerCategory;

    public function init()
    {
        $this->managerUser = \Yii::$container->get(ManagerUser::class);
        $this->managerCity = \Yii::$container->get(ManagerCity::class);
        $this->managerCategory = \Yii::$container->get(ManagerCategory::class);
        parent::init();
    }

    public function actionIndex()
    {
        $model = new UserEditModel();
        $user = $this->managerUser->getUserById(\Yii::$app->user->getId());
        $userCategories = $this->managerCategory->getCategoriesByUserId(\Yii::$app->user->getId());
        $allCategories = $this->managerCategory->getAllCategories();

//        echo "<pre>";
//        var_dump($allCategories->toArray());
//        die();

        $cities = $this->managerCity->getAllCities();
        $postData = \Yii::$app->request->post();
        if($model->load($postData) && $model->validate()) {
            echo "!!!";
            die();
        }

        return $this->render('index',[
            'user' => $user->toArray(),
            'cities' => $cities->toArray(),
            'userCategories' => $userCategories->toArray(),
            'allCategories' => $allCategories->toArray(),
            'userEditModel' => $model,
        ]);
    }
}
