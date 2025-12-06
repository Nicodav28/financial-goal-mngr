<?php

namespace App\Services;

use App\Models\ProgressLog;
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
        $data['owner_id'] = Auth::user()->id;
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

    /**
     * Calculate progress percentage for a goal based on progress logs.
     */
    public function calculateProgress($goalId): float
    {
        $goal = $this->goalRepository->findById($goalId);
        if (!$goal) {
            return 0.0;
        }
        $total = ProgressLog::where('goal_id', $goalId)->sum('amount');
        $target = $goal->target_amount;
        if ($target == 0) {
            return 0.0;
        }
        $percentage = min(100, ($total / $target) * 100);
        return round($percentage, 2);
    }

    /**
     * Retrieve progress logs for a goal.
     */
    public function getProgressLogs($goalId)
    {
        return ProgressLog::where('goal_id', $goalId)->orderBy('recorded_at', 'desc')->get();
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
