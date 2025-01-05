<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PorteiroAuthController;
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
    // Rota para editar um porteiro
    Route::get('porteiros/{id}/edit', [PorteiroAuthController::class, 'edit'])->name('porteiros.edit');

    // Rota para atualizar um porteiro
    Route::put('porteiros/{id}', [PorteiroAuthController::class, 'update'])->name('porteiros.update');

    // Rota para excluir um porteiro
    Route::delete('porteiros/{id}', [PorteiroAuthController::class, 'destroy'])->name('porteiros.destroy');
});

require __DIR__ . '/auth.php';
