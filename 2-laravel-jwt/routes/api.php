<?php

use App\Http\Controllers\AuthController;
use App\Models\Task;
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

Route::group([
    'middleware' => 'api',
    'prefix'     => 'auth',
], function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('jwt.auth')->group(function () {
    Route::get('/test', function () {
        return response()->json('bonjour');
    });
});
