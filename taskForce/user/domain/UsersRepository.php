<?php

namespace taskForce\user\domain;

interface UsersRepository
{
    public function getAllExecutors(): UsersList;

    public function getExecutorById(int $id): User;

    public function getExecutorsByFilter(array $filters = null): UsersList;

    public function getCustomerByTaskId(int $id): User;

    public function getAuthorByReviewId(int $id): User;

    public function createNewUser(User $user): bool;

    public function getAllUsers(): array;

}
