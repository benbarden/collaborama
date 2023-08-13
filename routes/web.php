<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicSite\WelcomeController;
use App\Http\Controllers\PublicSite\WaitingListController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [WelcomeController::class, 'show'])->name('welcome');

Route::match(['get', 'post'], '/join-waiting-list', [WaitingListController::class, 'signup'])->name('join-waiting-list');

