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
        return $this->goalRepository->findAll()->load('group');
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

    public function linkGoalToGroup($goalId, $groupId)
    {
        $goal = $this->goalRepository->findById($goalId);

        if (!$goal) {
            return response()->json(['error' => 'Goal not found'], 404);
        }

        $goal->group_id = $groupId;
        $goal->save();

        return $goal->load('group');
    }
}
