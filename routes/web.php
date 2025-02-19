<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\AnalysisController;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Lecturer Routes
    Route::middleware(['auth', 'lecturer'])->group(function () {
        Route::get('/lecturer/dashboard', [LecturerController::class, 'dashboard'])->name('lecturer.dashboard');
        
        // Questionnaire management routes
        Route::get('/questionnaires/create', [QuestionnaireController::class, 'create'])->name('questionnaires.create');
        Route::post('/questionnaires', [QuestionnaireController::class, 'store'])->name('questionnaires.store');
        
        // Analysis routes
        Route::get('/questionnaires/{questionnaire}/analysis', [AnalysisController::class, 'show'])->name('analysis.show');
        Route::get('/questionnaires/{questionnaire}/export/{format?}', [AnalysisController::class, 'export'])->name('analysis.export');
        
        // Question routes
        Route::post('/questionnaires/{questionnaire}/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
        
        Route::get('/questionnaires/{questionnaire}/edit', [QuestionnaireController::class, 'edit'])->name('questionnaires.edit');
        Route::put('/questionnaires/{questionnaire}', [QuestionnaireController::class, 'update'])->name('questionnaires.update');
        Route::delete('/questionnaires/{questionnaire}', [QuestionnaireController::class, 'destroy'])->name('questionnaires.destroy');
    });

    // Questionnaire routes accessible by both students and lecturers
    Route::get('/questionnaires', [QuestionnaireController::class, 'index'])->name('questionnaires.index');
    Route::get('/questionnaires/{questionnaire}', [QuestionnaireController::class, 'show'])->name('questionnaires.show');

    // Analysis Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/questionnaires/{questionnaire}/analysis', [AnalysisController::class, 'show'])
            ->name('questionnaires.analysis');
        Route::get('/questionnaires/{questionnaire}/export', [AnalysisController::class, 'export'])
            ->name('questionnaires.export');
    });

    // Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // User management routes
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        
        Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
    });

    // Student Routes
    Route::middleware(['auth', 'student'])->group(function () {
        Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/questionnaires/{questionnaire}/answer', [QuestionnaireController::class, 'showAnswerForm'])->name('questionnaires.answer');
        Route::post('/questionnaires/{questionnaire}/submit', [QuestionnaireController::class, 'submitAnswer'])->name('questionnaires.submit');
        Route::get('/questionnaires/{questionnaire}/response', [QuestionnaireController::class, 'showResponse'])->name('questionnaires.show-response');
        Route::post('/responses/{questionnaire}', [ResponseController::class, 'store'])->name('responses.store');
    });
});

require __DIR__.'/auth.php';
