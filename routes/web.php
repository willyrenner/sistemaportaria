<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PorteiroAuthController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ResponsavelController;
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

Route::get('/loggedin', function () {
    return view('loggedin');
});

Route::post('/callback', [AuthenticatedSessionController::class, 'handleSuapCallback']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas específicas para porteiros

Route::get('/autenticar', function () {
    return view('porteiro.login');
})->name('autenticar');



// Logout do porteiro
Route::post('/porteiro/logout', [PorteiroAuthController::class, 'logout'])->name('porteiro.logout');
Route::get('/porteiro/dashboard', [PorteiroAuthController::class, 'dashboard'])
    ->name('porteiro.dashboard');


Route::prefix('porteiros')->group(function () {
    Route::get('create', [PorteiroAuthController::class, 'create'])->name('porteiros.create');
    Route::post('login', [PorteiroAuthController::class, 'login'])->name('porteiro.login');
    Route::post('store', [PorteiroAuthController::class, 'store'])->name('porteiros.store');
    Route::get('/', [PorteiroAuthController::class, 'index'])->name('porteiros.index');
    Route::delete('{id}', [PorteiroAuthController::class, 'destroy'])->name('porteiros.destroy');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/porteiros', [PorteiroAuthController::class, 'index'])->name('porteiros.index');
    // ROTAS PORTEIROS
    Route::get('porteiros/{id}/edit', [PorteiroAuthController::class, 'edit'])->name('porteiros.edit');
    Route::put('porteiros/{id}', [PorteiroAuthController::class, 'update'])->name('porteiros.update');
    Route::delete('porteiros/{id}', [PorteiroAuthController::class, 'destroy'])->name('porteiros.destroy');

    // ROTAS ALUNOS
    Route::get('/alunos', [AlunoController::class, 'index'])->name('alunos.index'); 
    Route::post('/alunos', [AlunoController::class, 'store'])->name('alunos.store');
    Route::get('/alunos/{id}/editar', [AlunoController::class, 'edit'])->name('alunos.edit'); 
    Route::put('/alunos/{id}', [AlunoController::class, 'update'])->name('alunos.update'); 
    Route::delete('/alunos/{id}', [AlunoController::class, 'destroy'])->name('alunos.destroy'); 

    // ROTA CURSOS
    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::post('/cursos', [CursoController::class, 'store'])->name('cursos.store');
    Route::get('/cursos/{id}/editar', [CursoController::class, 'edit'])->name('cursos.edit'); 
    Route::put('/cursos/{id}', [CursoController::class, 'update'])->name('cursos.update'); 
    Route::delete('/cursos/{id}', [CursoController::class, 'destroy'])->name('cursos.destroy'); 

    // ROTA RESPONSAVEIS
    Route::get('/responsaveis', [ResponsavelController::class, 'index'])->name('responsaveis.index');
    Route::post('/responsaveis', [ResponsavelController::class, 'store'])->name('responsaveis.store');
    Route::get('/responsaveis/{id}/editar', [ResponsavelController::class, 'edit'])->name('responsaveis.edit'); 
    Route::put('/responsaveis/{id}', [ResponsavelController::class, 'update'])->name('responsaveis.update'); 
    Route::delete('/responsaveis/{id}', [ResponsavelController::class, 'destroy'])->name('responsaveis.destroy'); 
});

require __DIR__ . '/auth.php';
