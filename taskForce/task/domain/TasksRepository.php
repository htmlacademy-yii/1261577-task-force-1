<?php

namespace taskForce\task\domain;

interface TasksRepository
{
    public function getById(int $id) : Task;

    public function getByFilter(array $filters = null): TasksList;

    public function getAll(): TasksList;

    public function getCountTasksByExecutorId(int $id): int;

    public function getCountTasksByCustomerId(int $id): int;

    public function createNewTask(Task $task): Task;

    public function removeTaskById(int $id): void;

    public function addTaskImageRows(Image $image): void ;

    public function setExecutorForTask(int $user_id, int $task_id): Task;

    public function getAllActions(): array;

    public function setFailTaskStatus(int $task_id): void;

    public function setTaskStatus(string $status, int $task_id): void;

    public function getUsersTasksByStatus(string $status, int $user_id): TasksList;

//    public function getOverdueTasksWithNewStatus(): TasksList;
}
