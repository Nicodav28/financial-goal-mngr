<?php

namespace App\Repositories\Contracts;

interface GoalRepositoryInterface
{
    public function findAll(string $userId);

    public function findById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
