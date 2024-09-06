<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookItemController;
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

// Book Items
Route::post('bookItems/borrow/{id}', [BookItemController::class, 'borrow'])->middleware('auth:sanctum');
Route::put('bookItems/return/{id}', [BookItemController::class, 'return'])->middleware('auth:sanctum');
Route::get('bookItems/available/{id}', [BookItemController::class, 'availableBookItemsForBook']);

// Shift
Route::get('shifts/byId/{id}', [ShiftController::class, 'getShiftById'])->middleware(['auth:sanctum', 'abilities:shift-access']);
Route::get('shifts/byDate', [ShiftController::class, 'getShiftByDate'])->middleware(['auth:sanctum', 'abilities:shift-access']);
Route::post('shifts/open', [ShiftController::class, 'openShift'])->middleware(['auth:sanctum', 'abilities:shift-access']);
Route::put('shifts/addEmployee/{id}', [ShiftController::class, 'addEmployeeToShift'])->middleware(['auth:sanctum', 'ability:shift-access']);
Route::put('shifts/addNote/{id}', [ShiftController::class, 'addNoteToShift'])->middleware(['auth:sanctum', 'ability:shift-access']);
Route::put('shifts/close/{id}', [ShiftController::class, 'closeShift'])->middleware(['auth:sanctum', 'abilities:shift-access']);

Route::apiResources([
    'authors' => AuthorController::class,
    'publishers' => PublisherController::class,
    'books' => BookController::class,
    'users' => UserController::class,
    'employees' => EmployeeController::class,
    'shifts' => ShiftController::class,
    'bookItems' => BookItemController::class,
]);
