<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PorteiroAuthController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\RegistroSaidaController;
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
})->name('/');

Route::get('/dashboard', function () {
    $movimentacoes = app(RegistroSaidaController::class)->movimentacoes();
    return view('dashboard', compact('movimentacoes'));
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


Route::get('/porteiro/dashboard', [PorteiroAuthController::class, 'dashboard'])->name('porteiro.dashboard');
Route::post('/registros/registrar', [RegistroSaidaController::class, 'store'])->name('registros.registrar');
Route::post('/registros/registrarvisitante', [PorteiroAuthController::class, 'cadastrarVisitante'])->name('porteiro.registrarvisitante');
Route::post('/porteiro/logout', [PorteiroAuthController::class, 'logout'])->name('porteiro.logout');
Route::get('/porteiro/password-update', [PorteiroAuthController::class, 'passwordUpdateForm'])->name('porteiro.password.update');
Route::post('/porteiro/password/update', [PorteiroAuthController::class, 'updatePassword'])->name('porteiro.password.update.submit');
Route::put('/porteiro/confirmar-saida-visitante/{registro}', [PorteiroAuthController::class, 'confirmarSaidaVisitante'])->name('porteiro.confirmar-saida-visitante');
Route::get('/registros/visitantes', [PorteiroAuthController::class, 'registros'])->name('registros.visitantes');

Route::get('/porteiro/visitantes', [PorteiroAuthController::class, 'registrosVisitantes'])->name('porteiro.visitantes');
Route::get('/porteiro/alunos', [PorteiroAuthController::class, 'registrosAlunos'])->name('porteiro.index');

Route::prefix('porteiros')->group(function () {
    Route::get('create', [PorteiroAuthController::class, 'create'])->name('porteiros.create');
    Route::post('login', [PorteiroAuthController::class, 'login'])->name('porteiro.login');
    Route::post('store', [PorteiroAuthController::class, 'store'])->name('porteiros.store');
    Route::get('/', [PorteiroAuthController::class, 'index'])->name('porteiros.index');
    Route::delete('{id}', [PorteiroAuthController::class, 'destroy'])->name('porteiros.destroy');
});

Route::get('/alunos/{matricula}', function ($matricula) {
    $aluno = \App\Models\Aluno::where('matricula', $matricula)->first();

    if ($aluno) {
        return response()->json([
            'nome' => $aluno->nome,
            'matricula' => $aluno->matricula,
        ]);
    }

    return response()->json(['error' => 'Aluno não encontrado'], 404);
})->name('alunos.buscar');




Route::middleware(['auth'])->group(function () {
    // ROTAS PORTEIROS
    Route::get('/porteiros', [PorteiroAuthController::class, 'index'])->name('porteiros.index');
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

    // ROTAS REGISTRO DE SAÍDAS
    Route::get('/registros', [RegistroSaidaController::class, 'index'])->name('registros.index');
    Route::get('/registros/novo', [RegistroSaidaController::class, 'create'])->name('registros.create');
    Route::post('/registros', [RegistroSaidaController::class, 'store'])->name('registros.store');
    Route::put('/registros/{registro}/confirmar-saida', [RegistroSaidaController::class, 'confirmarSaida'])->name('registros.confirmar-saida');
    Route::get('/autorizar-menores', [RegistroSaidaController::class, 'autorizarSaidasMenores'])->name('autorizar-menores');
});


require __DIR__ . '/auth.php';
