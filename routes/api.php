<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/number1', function() {

    for($i = 1; $i <= 100; $i++) {
        if ($i % 15 === 0) {
            $output[] = 'Mari Berkarya';
            continue;
        }
        if ($i % 5 === 0) {
            $output[] = "Berkarya";
            continue;
        }
        if ($i % 3 === 0) {
            $output[] = "Mari";
            continue;
        }
        $output[] = $i;
    }

    return implode(',', $output);
});

Route::get('/number2/{input}', function($value) {
    $vLoop = (int) ceil($value/2);
    for ($i = $vLoop; $i >= 1; $i--) {
        echo str_repeat(" ", $vLoop - $i);
        echo str_repeat('*', 2 * $i - 1) . "\n";
    }
});

Route::get('/number3', UserController::class . '@index');
Route::post('/number3', UserController::class . '@store');
Route::get('/number3/{id}', UserController::class . '@detail');
Route::post('/number3/{id}', UserController::class . '@update');
Route::delete('/number3/{id}', UserController::class . '@destroy');
