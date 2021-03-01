<?php

use App\Models\Task;
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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware('api_token')->group(function () {
// Nous retourne toutes les tâches
    Route::get('/tasks', function () {
        return Task::all();
    });

// Nous retourne une tâche particulière
    Route::get('/tasks/{taskId}', function ($taskId) {
        return Task::findOrFail($taskId);
    });

// Modifie une tâche particulière
    Route::put('/tasks/{taskId}', function ($taskId, Request $request) {
        $task = Task::findOrFail($taskId);
        $task->update($request->all());
        return $task;
    });

// Supprimer une tâche particulière
    Route::delete('/tasks/{taskId}', function ($taskId) {
        return Task::findOrFail($taskId)->delete();
    });

// On ajoute une tâche
    Route::post('/tasks', function (Request $request) {
        return Task::create($request->all());
    });
});

