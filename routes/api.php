<?php

use App\Http\Controllers\ExpenseRequestController;
use App\Models\Expense;
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

Route::prefix('/api/v1/expense-requests')->middleware('auth')->group(function (){
    Route::get('/',[ExpenseRequestController::class,'list']);
    Route::get('/types',[ExpenseRequestController::class,'types']);
    Route::post('/',[ExpenseRequestController::class,'create']);
    Route::post('/confirm',[ExpenseRequestController::class,'confirm'])->can('confirm',Expense::class);
    Route::post('/reject',[ExpenseRequestController::class,'reject'])->can('reject',Expense::class);
});
