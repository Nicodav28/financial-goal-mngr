<?php

namespace App\Http\Middleware;

use App\Models\Goal;
use App\Resources\ApiResponseResource;
use App\Shared\ResponseHandler;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGoalOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response | ApiResponseResource
    {
        // Goal ID is expected as a route parameter named 'id' or 'goalId'
        $goalId = $request->route('id') ?? $request->route('goalId');
        if (!$goalId) {
            return ResponseHandler::response(400, 'EnsureGoalOwner', 'Goal ID not provided');
        }

        $goal = Goal::find($goalId);
        if (!$goal) {
            return ResponseHandler::response(404, 'EnsureGoalOwner', 'Goal not found');
        }

        $user = $request->attributes->get('authenticated_user');
        if (!$user) {
            return ResponseHandler::response(401, 'EnsureGoalOwner', 'Unauthenticated');
        }

        if ($goal->owner_id !== $user->id) {
            return ResponseHandler::response(403, 'EnsureGoalOwner', 'You do not have permission to access this goal');
        }

        // Attach the goal instance to the request for downstream usage
        $request->attributes->set('goal', $goal);
        return $next($request);
    }
}
