<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\BooksLoansController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth', [UsersController::class,'login']);
Route::middleware('admin')->group(function () {
    Route::post('users/register', [UsersController::class, 'registerUsers']);
    Route::post('books/add', [BooksController::class, 'addBook']);
    Route::put('book_loan/approve/{loan_id}',[BooksLoansController::class, 'approveBookLoan']);
    Route::put('book_loan/receive_back', [BooksLoansController::class, 'receiveBookBackFromTheUser']);
    Route::get('book_loan/loan_requests', [BooksLoansController::class, 'index']);
});

Route::middleware('user')->group(function () {
    Route::post('books_loan/borrow', [BooksLoansController::class, 'requestBookLoan']);
    Route::put('book_loan/extend/{loan_id}', [BooksLoansController::class, 'extendBookLoan']);
    Route::get('book_loan/{user_id}', [BooksLoansController::class, 'bookLoansPerUser']);
    Route::get('books/all', [BooksController::class, 'index']);
    Route::delete('books_loan/return/{id}', [BooksLoansController::class, 'returnBorrowedBook']);
});



