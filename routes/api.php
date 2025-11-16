<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ValidateAuthToken;
use Illuminate\Support\Facades\Route;


Route::get('/test', function () {
    return response()->json(['message' => 'API is working'], 200);
});


//todo: Must implement WebAuthn with laragear System for authentication with location, ip, device info, etc. to avoid fraud also implement rate limiting on sensitive routes like login, register, invite acceptance, etc.
//todo: one-time code email verification when uncommon actions are performed (e.g., login from new device or location) must be implemented too.
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(ValidateAuthToken::class);
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::group(['prefix' => 'group', 'middleware' => [ValidateAuthToken::class]], function () {
    Route::get('/', [GroupController::class, 'index']);
    Route::post('/', [GroupController::class, 'store']);
    Route::get('/{id}', [GroupController::class, 'show']);
    Route::put('/{id}', [GroupController::class, 'update']);
    Route::delete('/{id}', [GroupController::class, 'destroy']);
});

Route::group(['prefix' => 'invite'], function () {
    Route::get('/', [InviteController::class, 'index']);
    Route::post('/', [InviteController::class, 'store']);
    Route::get('/{id}', [InviteController::class, 'show']);
    Route::put('/{id}', [InviteController::class, 'update']);
    Route::delete('/{id}', [InviteController::class, 'destroy']);

    Route::post('/accept/{inviteCode}', [InviteController::class, 'acceptInvite']);
});

Route::group(['prefix' => 'goals',  'middleware' => [ValidateAuthToken::class]], function () {
    Route::get('/', [GoalController::class, 'index']);
    Route::post('/', [GoalController::class, 'store']);
    Route::get('/{id}', [GoalController::class, 'show']);
    Route::put('/{id}', [GoalController::class, 'update']);
    Route::delete('/{id}', [GoalController::class, 'destroy']);

    Route::post('/{goalId}/link-group/{groupId}', [GoalController::class, 'linkGoalToGroup']);
});

Route::group(['prefix' => 'contribution'], function () {
    Route::get('/', [ContributionController::class, 'index']);
    Route::post('/', [ContributionController::class, 'store']);
    Route::get('/{id}', [ContributionController::class, 'show']);
    Route::put('/{id}', [ContributionController::class, 'update']);
    Route::delete('/{id}', [ContributionController::class, 'destroy']);
});

Route::group(['prefix' => 'attachment'], function () {
    Route::get('/', [AttachmentController::class, 'index']);
    Route::post('/', [AttachmentController::class, 'store']);
    Route::get('/{id}', [AttachmentController::class, 'show']);
    Route::put('/{id}', [AttachmentController::class, 'update']);
    Route::delete('/{id}', [AttachmentController::class, 'destroy']);
});



