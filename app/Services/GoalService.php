<?php

namespace App\Services;

use App\Repositories\Contracts\GoalRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class GoalService
{
    public function __construct(private readonly GoalRepositoryInterface $goalRepository)
    {
        //
    }

    public function getAllGoals()
    {
        return $this->goalRepository->findAll(Auth::user()->id)->load('group', 'currency', 'owner');
    }

    public function getGoalById($id)
    {
        return $this->goalRepository->findById($id);
    }

    public function createGoal(array $data)
    {
        $data['owner_id'] = Auth::User()->id;
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
