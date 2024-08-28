<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::delete('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Books
Route::get('books/available', [BookController::class, 'available']);
Route::put('books/borrow/{id}', [BookController::class, 'borrow'])->middleware('auth:sanctum');
Route::put('books/return/{id}', [BookController::class, 'return'])->middleware('auth:sanctum');

// Shift
Route::get('shift/byId/{id}', [ShiftController::class, 'getShiftById'])->middleware(['auth:sanctum', 'abilities:shift-access']);
Route::get('shift/byDate', [ShiftController::class, 'getShiftByDate'])->middleware(['auth:sanctum', 'abilities:shift-access']);
Route::post('shift/open', [ShiftController::class, 'openShift'])->middleware(['auth:sanctum', 'abilities:shift-access']);
Route::put('shift/addEmployee/{id}', [ShiftController::class, 'addEmployeeToShift'])->middleware(['auth:sanctum', 'ability:shift-access']);
Route::put('shift/addNote/{id}', [ShiftController::class, 'addNoteToShift'])->middleware(['auth:sanctum', 'ability:shift-access']);
Route::put('shift/close/{id}', [ShiftController::class, 'closeShift'])->middleware(['auth:sanctum', 'abilities:shift-access']);

Route::apiResources([
    'authors' => AuthorController::class,
    'publishers' => PublisherController::class,
    'books' => BookController::class,
    'users' => UserController::class,
    'employees' => EmployeeController::class,
    'shifts' => ShiftController::class,
]);
