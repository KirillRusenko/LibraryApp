<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [UserController::class, 'register']);

Route::apiResources([
    'authors' => AuthorController::class,
    'publishers' => PublisherController::class,
    'books' => BookController::class,
    'users' => UserController::class,
    'employees' => EmployeeController::class,
]);