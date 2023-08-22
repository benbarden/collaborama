<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\PublicSite\WelcomeController;
use App\Http\Controllers\PublicSite\WaitingListController;

use App\Http\Controllers\Collab\TopicController;
use App\Http\Controllers\Collab\QuestionController;
use App\Http\Controllers\Collab\AnswerController;

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

Route::middleware('auth')->group(function () {

    Route::get('/user/dashboard', [ProfileController::class, 'dashboard'])->name('user.dashboard');

//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/collab/topic/create', [TopicController::class, 'create'])->name('collab.topic.create');
    Route::post('/collab/topic/store', [TopicController::class, 'store'])->name('collab.topic.store');

    Route::get('/collab/topic/manage/{topic}', [TopicController::class, 'manage'])->name('collab.topic.manage');

    Route::get('/collab/question/create/{topic}', [QuestionController::class, 'create'])->name('collab.question.create');
    Route::post('/collab/question/store/{topic}', [QuestionController::class, 'store'])->name('collab.question.store');

});

Route::get('/t/{shareCode?}', [TopicController::class, 'view'])->name('collab.topic.view');

Route::get('/t/{shareCode}/q/{question}/a', [AnswerController::class, 'create'])->name('collab.answer.create');
Route::post('/t/{shareCode}/q/{question}/a', [AnswerController::class, 'store'])->name('collab.answer.store');

require __DIR__.'/auth.php';
