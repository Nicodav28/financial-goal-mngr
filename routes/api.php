<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/test', function () {
    return response()->json(['message' => 'API is working'], 200);
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::group(['prefix' => 'group'], function () {
    Route::get('/', [GroupController::class, 'index']);
    Route::post('/', [GroupController::class, 'store']);
    Route::get('/{id}', [GroupController::class, 'show']);
    Route::put('/{id}', [GroupController::class, 'update']);
    Route::delete('/{id}', [GroupController::class, 'destroy']);
});

Route::group(['prefix' => 'invite'], function () {
    Route::get('/', [App\Http\Controllers\InviteController::class, 'index']);
    Route::post('/', [App\Http\Controllers\InviteController::class, 'store']);
    Route::get('/{id}', [App\Http\Controllers\InviteController::class, 'show']);
    Route::put('/{id}', [App\Http\Controllers\InviteController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\InviteController::class, 'destroy']);

    Route::post('/accept/{inviteCode}', [App\Http\Controllers\InviteController::class, 'acceptInvite']);
});

Route::group(['prefix' => 'goal'], function () {
    Route::get('/', [App\Http\Controllers\GoalController::class, 'index']);
    Route::post('/', [App\Http\Controllers\GoalController::class, 'store']);
    Route::get('/{id}', [App\Http\Controllers\GoalController::class, 'show']);
    Route::put('/{id}', [App\Http\Controllers\GoalController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\GoalController::class, 'destroy']);

    Route::post('/{goalId}/link-group/{groupId}', [App\Http\Controllers\GoalController::class, 'linkGoalToGroup']);
});

Route::group(['prefix' => 'contribution'], function () {
    Route::get('/', [App\Http\Controllers\ContributionController::class, 'index']);
    Route::post('/', [App\Http\Controllers\ContributionController::class, 'store']);
    Route::get('/{id}', [App\Http\Controllers\ContributionController::class, 'show']);
    Route::put('/{id}', [App\Http\Controllers\ContributionController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\ContributionController::class, 'destroy']);

    // Route::post('/{contributionId}/link-goal/{goalId}', [App\Http\Controllers\ContributionController::class, 'linkContributionToGoal']);
});



