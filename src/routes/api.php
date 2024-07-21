<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test/', function() {
    return \App\Models\Author::all();
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [UserController::class, 'register']);

Route::apiResources([
    'authors' => AuthorController::class,
    'publishers' => PublisherController::class,
    'books' => BookController::class,
    'users' => UserController::class,
    'employees' => EmployeeController::class,
]);
