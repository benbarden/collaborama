<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\PublicSite\WelcomeController;
use App\Http\Controllers\PublicSite\WaitingListController;

use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;

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

    Route::get('/collab/topic/delete/{topic}', [TopicController::class, 'delete'])->name('collab.topic.delete');
    Route::get('/collab/topic/close/{topic}', [TopicController::class, 'close'])->name('collab.topic.close');
    Route::get('/collab/topic/reopen/{topic}', [TopicController::class, 'reopen'])->name('collab.topic.reopen');

    Route::get('/t/{shareCode}/q/{question}/a', [AnswerController::class, 'create'])->name('collab.answer.create');
    Route::post('/t/{shareCode}/q/{question}/a', [AnswerController::class, 'store'])->name('collab.answer.store');

    Route::get('/t/{shareCode}/q/{question}/nd-on', [AnswerController::class, 'needsDiscussionOn'])->name('collab.answer.needs-discussion-on');
    Route::get('/t/{shareCode}/q/{question}/nd-off', [AnswerController::class, 'needsDiscussionOff'])->name('collab.answer.needs-discussion-off');

    Route::get('/t/{shareCode}/q/{question}/dk-on', [AnswerController::class, 'dontKnowOn'])->name('collab.answer.dont-know-on');
    Route::get('/t/{shareCode}/q/{question}/dk-off', [AnswerController::class, 'dontKnowOff'])->name('collab.answer.dont-know-off');

});

Route::get('/t/{shareCode?}', [TopicController::class, 'view'])->name('collab.topic.view');

require __DIR__.'/auth.php';

Route::middleware('auth.staff')->group(function() {

    Route::get('/staff/dashboard', [StaffDashboardController::class, 'show'])->name('staff.dashboard');

    Route::get('/staff/invite-from-waiting-list/{waitingListUser}', [StaffDashboardController::class, 'inviteFromWaitingList'])->name('staff.invite-from-waiting-list');

});
