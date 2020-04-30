<?php

namespace taskForce\task\infrastructure;

use frontend\models\Task as modelTask;
use taskForce\share\StringHelper;
use taskForce\task\domain\TaskNotFoundException;
use taskForce\task\domain\Task;
use taskForce\task\domain\TasksList;
use taskForce\task\domain\TasksRepository;
use taskForce\task\infrastructure\builder\ArTaskBuilder;
use taskForce\task\infrastructure\filters\ArTaskFilter;
use yii\web\NotFoundHttpException;

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
}