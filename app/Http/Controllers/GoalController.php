<?php

namespace App\Http\Controllers;

use App\Services\GoalService;
use App\Shared\ResponseHandler;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function __construct(private readonly GoalService $goalService)
    {
        //
    }

    public function index()
    {
        try {
            $goals = $this->goalService->getAllGoals();
            return ResponseHandler::response(200, 'Goal:index', null, null, $goals);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Goal:index', 'Failed to retrieve goals', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $goal = $this->goalService->getGoalById($id);
            if (!$goal) {
                return ResponseHandler::response(404, 'Goal:show', 'Goal not found', null);
            }
            return ResponseHandler::response(200, 'Goal:show', null, null, $goal);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Goal:show', 'Failed to retrieve goal', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $goal = $this->goalService->createGoal($request->all());
            return ResponseHandler::response(201, 'Goal:store', null, null, $goal);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Goal:store', 'Failed to create goal', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $goal = $this->goalService->updateGoal($id, $request->all());
            return ResponseHandler::response(200, 'Goal:update', null, null, $goal);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Goal:update', 'Failed to update goal', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->goalService->deleteGoal($id);
            return ResponseHandler::response(200, 'Goal:destroy', 'Goal deleted successfully', null);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Goal:destroy', 'Failed to delete goal', $e->getMessage());
        }
    }

    public function linkGoalToGroup(Request $request, $goalId, $groupId)
    {
        try {
            $goalWithLinkedGroup = $this->goalService->linkGoalToGroup($goalId, $groupId);
            return ResponseHandler::response(200, 'Goal:linkGoalToGroup', null, null, $goalWithLinkedGroup);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Goal:linkGoalToGroup', 'Failed to link goal to group', $e->getMessage());
        }
    }
}
