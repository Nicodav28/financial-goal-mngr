<?php

namespace App\Http\Controllers;

use App\Services\ContributionService;
use App\Shared\ResponseHandler;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function __construct(private readonly ContributionService $contributionService)
    {
        //
    }

    public function index()
    {
        try {
            $contributions = $this->contributionService->getAllContributions();
            return ResponseHandler::response(200, 'Contribution:index', null, null, $contributions);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Contribution:index', 'Failed to retrieve contributions', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $contribution = $this->contributionService->getContributionById($id);
            if (!$contribution) {
                return ResponseHandler::response(404, 'Contribution:show', 'Contribution not found', null);
            }
            return ResponseHandler::response(200, 'Contribution:show', null, null, $contribution);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Contribution:show', 'Failed to retrieve contribution', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $contribution = $this->contributionService->createContribution($request->all());
            return ResponseHandler::response(201, 'Contribution:store', null, null, $contribution);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Contribution:store', 'Failed to create contribution', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $contribution = $this->contributionService->updateContribution($id, $request->all());
            return ResponseHandler::response(200, 'Contribution:update', null, null, $contribution);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Contribution:update', 'Failed to update contribution', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->contributionService->deleteContribution($id);
            return ResponseHandler::response(200, 'Contribution:destroy', 'Contribution deleted successfully', null);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Contribution:destroy', 'Failed to delete contribution', $e->getMessage());
        }
    }
}
