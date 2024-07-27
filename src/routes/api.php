<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Users
Route::post('users/register', [UserController::class, 'register']);

// Books
Route::get('books/available', [BookController::class, 'available']);
Route::put('books/borrow/{id}', [BookController::class, 'borrow']);

Route::apiResources([
    'authors' => AuthorController::class,
    'publishers' => PublisherController::class,
    'books' => BookController::class,
    'users' => UserController::class,
    'employees' => EmployeeController::class,
]);
