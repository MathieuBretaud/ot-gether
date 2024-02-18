<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminTagController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ParticipateController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::get('/check-email', [AuthController::class, 'checkEmail']);
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', function (Request $request) {
            return $request->user();
        });
        Route::get('/user/myevents', [UserController::class, 'myEvents']);
        Route::post('/events/{event}/participate', [ParticipateController::class, 'participate']);
        Route::post('/events/{event}/unsubscribe', [ParticipateController::class, 'unsubscribe']);
        Route::post('/events/{event}/reports', [ReportController::class, 'reportEvent']);
        Route::apiResources([
            'events' => EventController::class,
            'event-tags' => TagController::class,
            'event-categories' => CategoryController::class,
            'users' => UserController::class,
        ]);
        // Route::get('/events/{event}/messages', [ChatController::class], 'index');
        Route::get('/events/{event}/messages', [ChatController::class, 'fetchMessages']);
        Route::post('/events/{event}/messages', [ChatController::class, 'sendMessages']);
    });
});

Route::prefix('admin')->group(function () {
    Route::middleware(['auth:sanctum','admin'])->group(function () {
        Route::resource('admin-events', AdminEventController::class);
        Route::resource('admin-categories', AdminCategoryController::class);
        Route::resource('admin-users', AdminUserController::class);
        Route::resource('admin-tags', AdminTagController::class);
    });
});
