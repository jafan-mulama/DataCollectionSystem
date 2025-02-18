<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResponseController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Questionnaire routes
    Route::resource('questionnaires', QuestionnaireController::class);
    
    // Question routes (nested under questionnaires)
    Route::post('questionnaires/{questionnaire}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    
    // Response routes
    Route::post('questionnaires/{questionnaire}/responses', [ResponseController::class, 'store'])->name('responses.store');
    Route::get('questionnaires/{questionnaire}/responses', [ResponseController::class, 'index'])->name('responses.index');
    Route::get('questionnaires/{questionnaire}/responses/export', [ResponseController::class, 'export'])->name('responses.export');
});
