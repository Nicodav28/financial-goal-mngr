<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\ProgressLog;
use App\Shared\ResponseHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProgressController extends Controller
{
    /**
     * List progress logs for a specific goal.
     */
    public function index($goalId)
    {
        try {
            $goal = Goal::find($goalId);
            if (!$goal) {
                return ResponseHandler::response(404, 'Progress:index', 'Goal not found', null);
            }
            $logs = ProgressLog::where('goal_id', $goalId)->orderBy('recorded_at', 'desc')->get();
            return ResponseHandler::response(200, 'Progress:index', null, null, $logs);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Progress:index', 'Failed to retrieve progress logs', $e->getMessage());
        }
    }

    /**
     * Store a new progress entry for a goal.
     */
    public function store(Request $request, $goalId)
    {
        try {
            $goal = Goal::find($goalId);
            if (!$goal) {
                return ResponseHandler::response(404, 'Progress:store', 'Goal not found', null);
            }
            $data = $request->only([ 'amount' ]);
            $data['goal_id'] = $goalId;
            $data['recorded_at'] = now();
            $log = ProgressLog::create($data);
            return ResponseHandler::response(201, 'Progress:store', null, null, $log);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Progress:store', 'Failed to add progress', $e->getMessage());
        }
    }

    /**
     * Export progress logs as CSV.
     */
    public function exportCsv($goalId)
    {
        try {
            $goal = Goal::find($goalId);
            if (!$goal) {
                return ResponseHandler::response(404, 'Progress:exportCsv', 'Goal not found', null);
            }
            $logs = ProgressLog::where('goal_id', $goalId)->orderBy('recorded_at', 'desc')->get();
            $callback = function () use ($logs) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [ 'id', 'goal_id', 'amount', 'recorded_at' ]);
                foreach ($logs as $log) {
                    fputcsv($handle, [ $log->id, $log->goal_id, $log->amount, $log->recorded_at ]);
                }
                fclose($handle);
            };
            return new StreamedResponse($callback, 200, [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="goal_' . $goalId . '_progress.csv"',
            ]);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Progress:exportCsv', 'Failed to export CSV', $e->getMessage());
        }
    }
}