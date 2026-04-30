<?php

use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\EtudiantController;
use App\Http\Controllers\Web\EnseignantController;
use App\Http\Controllers\Web\AdminWebController;
use App\Http\Controllers\Web\ProfilController;
use Illuminate\Support\Facades\Route;

// Route racine
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Routes publiques
Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);
Route::get('/register', [AuthWebController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthWebController::class, 'register']);

// Routes authentifiées
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::delete('/profil', [ProfilController::class, 'destroy'])->name('profil.destroy');
});

// Routes administrateur
Route::middleware(['auth', 'role:administrateur'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/utilisateurs', [AdminWebController::class, 'utilisateurs'])->name('utilisateurs');
    Route::get('/utilisateurs/create', [AdminWebController::class, 'createUtilisateur'])->name('utilisateurs.create');
    Route::post('/utilisateurs', [AdminWebController::class, 'storeUtilisateur'])->name('utilisateurs.store');
    Route::get('/utilisateurs/{user}/edit', [AdminWebController::class, 'editUtilisateur'])->name('utilisateurs.edit');
    Route::put('/utilisateurs/{user}', [AdminWebController::class, 'updateUtilisateur'])->name('utilisateurs.update');
    Route::post('/utilisateurs/{user}/activer', [AdminWebController::class, 'activerUtilisateur'])->name('utilisateurs.activer');
    Route::post('/utilisateurs/{user}/desactiver', [AdminWebController::class, 'desactiverUtilisateur'])->name('utilisateurs.desactiver');
    Route::delete('/utilisateurs/{user}', [AdminWebController::class, 'supprimerUtilisateur'])->name('utilisateurs.supprimer');
    Route::get('/statistiques', [AdminWebController::class, 'statistiques'])->name('statistiques');
    Route::get('/reponses', [AdminWebController::class, 'reponses'])->name('reponses');
});

// Routes enseignant
Route::middleware(['auth', 'role:enseignant'])->prefix('enseignant')->name('enseignant.')->group(function () {
    Route::get('/dashboard', [EnseignantController::class, 'dashboard'])->name('dashboard');
    
    // CRUD Cours
    Route::get('/cours', [EnseignantController::class, 'indexCours'])->name('cours.index');
    Route::get('/cours/create', [EnseignantController::class, 'createCours'])->name('cours.create');
    Route::post('/cours', [EnseignantController::class, 'storeCours'])->name('cours.store');
    Route::get('/cours/{cours}', [EnseignantController::class, 'showCours'])->name('cours.show');
    Route::get('/cours/{cours}/edit', [EnseignantController::class, 'editCours'])->name('cours.edit');
    Route::put('/cours/{cours}', [EnseignantController::class, 'updateCours'])->name('cours.update');
    Route::delete('/cours/{cours}', [EnseignantController::class, 'destroyCours'])->name('cours.destroy');
    
    // CRUD Leçons
    Route::get('/cours/{cours}/lecon/create', [EnseignantController::class, 'createLecon'])->name('lecon.create');
    Route::post('/cours/{cours}/lecon', [EnseignantController::class, 'storeLecon'])->name('lecon.store');
    Route::get('/lecon/{lecon}/edit', [EnseignantController::class, 'editLecon'])->name('lecon.edit');
    Route::put('/lecon/{lecon}', [EnseignantController::class, 'updateLecon'])->name('lecon.update');
    Route::delete('/lecon/{lecon}', [EnseignantController::class, 'destroyLecon'])->name('lecon.destroy');
    
    // CRUD Quiz
    Route::get('/cours/{cours}/quiz/create', [EnseignantController::class, 'createQuiz'])->name('quiz.create');
    Route::post('/cours/{cours}/quiz', [EnseignantController::class, 'storeQuiz'])->name('quiz.store');
    Route::get('/quiz/{quiz}', [EnseignantController::class, 'showQuiz'])->name('quiz.show');
    Route::get('/quiz/{quiz}/edit', [EnseignantController::class, 'editQuiz'])->name('quiz.edit');
    Route::put('/quiz/{quiz}', [EnseignantController::class, 'updateQuiz'])->name('quiz.update');
    Route::delete('/quiz/{quiz}', [EnseignantController::class, 'destroyQuiz'])->name('quiz.destroy');
    
    // CRUD Questions
    Route::get('/quiz/{quiz}/question/create', [EnseignantController::class, 'createQuestion'])->name('question.create');
    Route::post('/quiz/{quiz}/question', [EnseignantController::class, 'storeQuestion'])->name('question.store');
    Route::get('/question/{question}/edit', [EnseignantController::class, 'editQuestion'])->name('question.edit');
    Route::put('/question/{question}', [EnseignantController::class, 'updateQuestion'])->name('question.update');
    Route::delete('/question/{question}', [EnseignantController::class, 'destroyQuestion'])->name('question.destroy');
    
    Route::get('/resultats', [EnseignantController::class, 'resultats'])->name('resultats');
});

// Routes étudiant
Route::middleware(['auth', 'role:etudiant'])->prefix('etudiant')->name('etudiant.')->group(function () {
    Route::get('/dashboard', [EtudiantController::class, 'dashboard'])->name('dashboard');
    Route::get('/catalogue', [EtudiantController::class, 'catalogue'])->name('catalogue');
    Route::post('/cours/{cours}/inscrire', [EtudiantController::class, 'sInscrire'])->name('cours.inscrire');
    Route::get('/cours', [EtudiantController::class, 'mesCours'])->name('cours.index');
    Route::get('/cours/{cours}', [EtudiantController::class, 'showCours'])->name('cours.show');
    Route::get('/lecon/{lecon}', [EtudiantController::class, 'showLecon'])->name('lecon.show');
    Route::post('/lecon/{lecon}/complete', [EtudiantController::class, 'completeLecon'])->name('lecon.complete');
    Route::get('/quiz/{quiz}', [EtudiantController::class, 'showQuiz'])->name('quiz.show');
    Route::get('/quiz/{quiz}/debug', [EtudiantController::class, 'debugQuiz'])->name('quiz.debug');
    Route::post('/quiz/{quiz}/submit', [EtudiantController::class, 'submitQuiz'])->name('quiz.submit');
    Route::get('/resultats', [EtudiantController::class, 'resultats'])->name('resultats');
});
