<?php

use App\Enums\UserRole;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseModerationController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ModuleController;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::middleware(['auth:sanctum', 'checkUserRole:' . UserRole::ADMIN])
    ->prefix('admin')->group(function () {
        Route::get('', function () {
            return 'admin';
        });
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('categories/{id}', [CategoryController::class, 'show']);
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
    });

Route::middleware(['auth:sanctum', 'checkUserRole:' . UserRole::MODERATOR])
    ->prefix('moderator')->group(function () {
        Route::get('', function () {
            return 'moderator';
        });

        Route::prefix('courses')->group(function () {
            Route::get('', [CourseController::class, 'index']);
            Route::get('{course}', [CourseController::class, 'show']);
            Route::get('{course}/analysis', [CourseModerationController::class, 'startCourseAnalysis']);
            Route::get('{course}/modules/{module}/lessons/{lesson}/analysis', [CourseModerationController::class, 'startLessonAnalysis']);
            Route::post('handleResponseEdenAI', [CourseModerationController::class, 'handleResponseEdenAI']);
        });
    });

Route::middleware(['auth:sanctum', 'checkUserRole:' . UserRole::INSTRUCTOR])
    ->prefix('instructor')->group(function () {
        Route::get('', function () {
            return 'instructor';
        });
        Route::prefix('courses')->group(function () {
            Route::get('', [CourseController::class, 'index']);
            Route::get('{course}', [CourseController::class, 'show']);
            Route::post('', [CourseController::class, 'store']);
            Route::put('{course}', [CourseController::class, 'update']);
            Route::delete('{course}', [CourseController::class, 'destroy']);

            Route::prefix('{course}/modules')->group(function () {
                Route::post('', [ModuleController::class, 'store']);
                Route::get('', [ModuleController::class, 'index']);
                Route::put('{module}', [ModuleController::class, 'update']);
                Route::delete('{module}', [ModuleController::class, 'destroy']);

                Route::prefix('{module}/lessons')->group(function () {
                    Route::post('', [LessonController::class, 'store']);
                    Route::get('', [LessonController::class, 'index']);
                    Route::get('{lesson}', [LessonController::class, 'show']);
                    Route::put('{lesson}', [LessonController::class, 'update']);
                    Route::delete('{lesson}', [LessonController::class, 'destroy']);
                });
            });
        });
    });

Route::middleware(['auth:sanctum', 'checkUserRole:' . UserRole::LEARNER . ',' . UserRole::INSTRUCTOR])
    ->group(function () {
        Route::get('', function () {
            return 'leaner';
        });
    });

Route::prefix('eden-ai')->group(function () {
    Route::post('/webhook/moderation-video/{lesson}', [CourseModerationController::class, 'handleVideoReviewResult'])->name('webhook.handle-video-review-result');
});

Route::get('/test', function () {
    return 'test api successfully.';
});

Route::fallback(function () {
    abort(404, 'API resource not found');
});

require __DIR__ . '/auth.php';
