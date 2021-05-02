<?php

namespace frontend\controllers;

use taskForce\discussion\application\ManagerDiscussion;
use taskForce\task\application\ManagerTask;
use taskForce\user\application\ManagerUser;

class MyListController extends SecuredController
{
    /**
     * @var ManagerTask
     */
    private $managerTask;

    /**
     * @var ManagerUser
     */
    private $managerUser;

    /**
     * @var ManagerDiscussion
     */
    private $managerDiscussion;

    public function init()
    {
        $this->managerTask = \Yii::$container->get(ManagerTask::class);
        $this->managerUser = \Yii::$container->get(ManagerUser::class);
        $this->managerDiscussion = \Yii::$container->get(ManagerDiscussion::class);
        parent::init();
    }

    public function actionIndex(string $status = 'new')
    {
        $user_id = \Yii::$app->user->getId();
        if($this->managerUser->isExecutor($user_id) && $status == 'cancel') {
            $status = 'fail';
        }
        $tasks = $this->managerTask->getUsersTasksByStatus($status, $user_id);
        $tasksRender = [];
        foreach ($tasks as $task) {
            if($status == 'overdue' && $task->deadline > date('Y-m-d H:i:s')) {
                continue;
            }
            if($status == 'new' && $task->deadline < date('Y-m-d H:i:s')) {
                continue;
            }
            $tasksData = $task->toArray();
            $tasksData['messages'] = $this->managerDiscussion
                ->getCountNewMessagesByTaskId($task->id,$this->managerUser->isExecutor($user_id),false);
            if($status == 'overdue') {
                $tasksData['status']['translation'] = 'hidden';
            }
            $tasksRender[] = $tasksData;
        }

        return $this->render('index',[
            'tasksData' => $tasksRender,
        ]);
    }
}
