<?php

namespace taskForce\task\infrastructure;

use frontend\models\Role;
use frontend\models\Task as modelTask;
use frontend\models\TaskImage;
use frontend\models\TaskStatus;
use frontend\models\User;
use taskForce\task\action\CancelAction;
use taskForce\task\action\CompleteAction;
use taskForce\task\action\ExecuteAction;
use taskForce\task\action\FailAction;
use taskForce\task\action\ResponseAction;
use taskForce\task\domain\Image;
use taskForce\task\domain\exceptions\TaskNotFoundException;
use taskForce\task\domain\Task;
use taskForce\task\domain\TasksList;
use taskForce\task\domain\TasksRepository;
use taskForce\task\infrastructure\builder\ArTaskBuilder;
use taskForce\task\infrastructure\filters\ArTaskFilter;
use taskForce\share\Exceptions\NotSaveException;

class ArTasksRepository implements TasksRepository
{
    /**
     * @var ArTaskBuilder
     */
    private $builder;

    /**
     * ArTasksRepository constructor.
     * @param ArTaskBuilder $builder
     */
    public function __construct(ArTaskBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param int $id
     * @return Task
     * @throws TaskNotFoundException
     */
    public function getById(int $id):Task
    {
        $task = modelTask::findOne($id);
        if($task === null) {
            throw new TaskNotFoundException();
        }

        return $this->builder->build($task,true);
    }

    /**
     * @param array|null $filters
     * @return TasksList
     */
    public function getByFilter(array $filters = null): TasksList
    {
        $tasks = modelTask::find()->orderBy('created_at DESC');
        if(!is_null($filters)) {
            $filter = new ArTaskFilter($tasks);
            $tasks = $filter->apply($filters);
        }
        $tasks = $tasks->all();
        $tasksList = new TasksList();
        foreach ($tasks as $task) {
            $tasksList[] = $this->builder->build($task);
        }

        return $tasksList;
    }

    /**
     * @return TasksList
     */
    public function getAll(): TasksList
    {
        $tasks = modelTask::find()->all();
        $tasksList = new TasksList();
        foreach ($tasks as $task) {
            $tasksList[] = $this->builder->build($task);
        }

        return $tasksList;
    }

    /**
     * @param int $id
     * @return int
     */
    public function getCountTasksByExecutorId(int $id): int
    {
        return modelTask::find()->where(['executor_id' => $id])->count();
    }

    /**
     * @param int $id
     * @return int
     */
    public function getCountTasksByCustomerId(int $id): int
    {
        return modelTask::find()->where(['user_id' => $id])->count();
    }

    /**
     * @param Task $task
     * @return Task
     * @throws NotSaveException
     */
    public function createNewTask(Task $task): Task
    {
        $newTask = new modelTask();
        $newTask->short = $task->shortName;
        $newTask->description = $task->description;
        if($task->category) {
            $newTask->category_id = $task->category->id;
        }
        $newTask->budget = $task->budget;
        $newTask->deadline = $task->deadline;
        if($task->author) {
            $newTask->user_id = $task->author->id;
        }
        $newTask->status_id = TaskStatus::findOne(['name' => TaskStatus::NAME_STATUS_NEW])->id;
        if($task->location) {
            $newTask->latitude = $task->location->latitude;
            $newTask->longitude = $task->location->longitude;
        }
        $newTask->address = $task->address;

        if(!$newTask->save()) {
            throw new NotSaveException();
        }

        return $this->builder->build($newTask);
    }

    /**
     * @param int $id
     */
    public function removeTaskById(int $id): void
    {
        modelTask::deleteAll(['id' => $id]);
    }

    /**
     * @param Image $image
     * @throws NotSaveException
     */
    public function addTaskImageRows(Image $image): void
    {
        $taskImage = new TaskImage();
        $taskImage->path = $image->path;
        $taskImage->task_id = $image->task_id;
        if(!$taskImage->save()) {
            throw new NotSaveException();
        }
    }

    /**
     * @param int $user_id
     * @param int $task_id
     * @return Task
     * @throws NotSaveException
     * @throws TaskNotFoundException
     */
    public function setExecutorForTask(int $user_id, int $task_id): Task
    {
        $task = modelTask::findOne(['id' => $task_id]);
        if($task === null) {
            throw new TaskNotFoundException();
        }
        $task->executor_id = $user_id;
        $task->status_id = TaskStatus::findOne(['name' => TaskStatus::NAME_STATUS_JOB])->id;
        if (!$task->save()) {
            throw new NotSaveException();
        }

        return $this->builder->build($task);
    }

    /**
     * @return array
     */
    public function getAllActions(): array
    {
        return [ExecuteAction::class,CancelAction::class,CompleteAction::class,FailAction::class,ResponseAction::class];
    }

    /**
     * @param int $task_id
     * @throws NotSaveException
     * @throws TaskNotFoundException
     */
    public function setFailTaskStatus(int $task_id): void
    {
        $task = modelTask::findOne(['id' => $task_id]);
        if($task === null) {
            throw new TaskNotFoundException();
        }
        $task->status_id = TaskStatus::findOne(['name' => TaskStatus::NAME_STATUS_FAIL])->id;
        if (!$task->save()) {
            throw new NotSaveException();
        }
    }

    /**
     * @param string $status
     * @param int $task_id
     * @throws NotSaveException
     * @throws TaskNotFoundException
     */
    public function setTaskStatus(string $status, int $task_id): void
    {
        $task = modelTask::findOne(['id' => $task_id]);
        if($task === null) {
            throw new TaskNotFoundException();
        }
        $task->status_id = TaskStatus::findOne(['name' => $status])->id;
        if (!$task->save()) {
            throw new NotSaveException();
        }
    }

    public function getUsersTasksByStatus(string $status, int $user_id): TasksList
    {
        $user = User::findOne(['id' => $user_id]);
        $role = Role::findOne(['id' => $user->role_id]);

        if($status == 'cancel' && $role->name == Role::CUSTOMER_ROLE) {
            $statusFind = TaskStatus::find()->where(['translation' => ['cancel','fail']])->all();
        } else {
            $statusFind = TaskStatus::find()->where(['translation' => $status])->all();
        }
        if($statusFind == null) {
            $statusFind = TaskStatus::find()->where(['translation' => 'new'])->all();
        }

        $statusesId = [];
        foreach ($statusFind as $item) {
            $statusesId[] = $item->id;
        }

        if($role->name == Role::CUSTOMER_ROLE) {
            $tasks = modelTask::find()->where(['user_id' => $user_id, 'status_id' => $statusesId])->all();
        } else {
            $tasks = modelTask::find()->where(['executor_id' => $user_id, 'status_id' => $statusesId])->all();
        }
        $tasksList = new TasksList();
        foreach ($tasks as $task) {
            $tasksList[] = $this->builder->build($task);
        }

        return $tasksList;
    }
}
