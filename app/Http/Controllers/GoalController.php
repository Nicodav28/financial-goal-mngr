<?php

namespace App\Http\Controllers;

use App\Services\GoalService;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function __construct(private readonly GoalService $goalService)
    {
        //
    }

    public function index()
    {
        return $this->goalService->getAllGoals();
    }

    public function show($id)
    {
        return $this->goalService->getGoalById($id);
    }

    public function store(Request $request)
    {
        return $this->goalService->createGoal($request->all());
    }

    public function update(Request $request, $id)
    {
        return $this->goalService->updateGoal($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->goalService->deleteGoal($id);
    }
}
