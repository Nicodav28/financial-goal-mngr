<?php

namespace App\Services;

use App\Repositories\Contracts\GoalRepositoryInterface;

class GoalService
{
    public function __construct(private readonly GoalRepositoryInterface $goalRepository)
    {
        //
    }

    public function getAllGoals()
    {
        return $this->goalRepository->findAll();
    }

    public function getGoalById($id)
    {
        return $this->goalRepository->findById($id);
    }

    public function createGoal(array $data)
    {
        return $this->goalRepository->create($data);
    }

    public function updateGoal($id, array $data)
    {
        return $this->goalRepository->update($id, $data);
    }

    public function deleteGoal($id)
    {
        return $this->goalRepository->delete($id);
    }
}
