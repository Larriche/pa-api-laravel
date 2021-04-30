<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\ExpensesTracker\Controllers\WalletsController;
use App\Http\ExpensesTracker\Controllers\IncomeSourcesController;
use App\Http\ExpensesTracker\Controllers\ExpenseCategoriesController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Wallets
    Route::get('wallets', [ WalletsController::class, 'index' ]);
    Route::post('wallets', [ WalletsController::class, 'store' ]);
    Route::post('wallets/transfer', [ WalletsController::class, 'transferFunds']);
    Route::get('wallets/{wallet}', [ WalletsController::class, 'show' ]);
    Route::put('wallets/{wallet}', [ WalletsController::class, 'update' ]);
    Route::delete('wallets/{wallet}', [ WalletsController::class, 'destroy' ]);

    // Income sources
    Route::get('income_sources', [ IncomeSourcesController::class, 'index' ]);
    Route::post('income_sources', [ IncomeSourcesController::class, 'store' ]);
    Route::get('income_sources/{income_source}', [ IncomeSourcesController::class, 'show' ]);
    Route::put('income_sources/{income_source}', [ IncomeSourcesController::class, 'update' ]);
    Route::delete('income_sources/{income_source}', [ IncomeSourcesController::class, 'destroy' ]);

    // Expense categories
    Route::get('expense_categories', [ ExpenseCategoriesController::class, 'index' ]);
    Route::post('expense_categories', [ ExpenseCategoriesController::class, 'store' ]);
});

Route::post('login', LoginController::class);
Route::post('register', SignUpController::class);
