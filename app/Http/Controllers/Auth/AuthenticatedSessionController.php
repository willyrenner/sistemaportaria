<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function handleSuapCallback(Request $request)
    {
        // Log para verificar se a função está sendo chamada
        Log::info('handleSuapCallback foi chamado.');

        // Obtém o token de acesso da requisição
        $token = $request->input('token');

        // Log para verificar o token recebido
        Log::info('Token recebido: ' . $token);

        // Verifica se o token foi fornecido
        if (!$token) {
            return response()->json(['error' => 'Token não fornecido.'], 400);
        } else {
            $client = new Client();
            $response = $client->request('GET', 'https://suap.ifrn.edu.br/api/eu/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $nome = isset($data['nome_usual']) ? $data['nome_usual'] : 'Nome não disponível';
            $email = isset($data['email']) ? $data['email'] : 'E-mail não disponível';
            $matricula = isset($data['identificacao']) ? $data['identificacao'] : 'identificacao não disponível';

            // TO-DO: NÃO DEIXAR ALUNO ACESSAR O SISTEMA
            if (strtolower(isset($data['tipo_usuario'])) === 'aluno') {
                return redirect('/')->back()->with('error', 'Usuário não pode acessar o sistema!!!');
            }

            // Cria ou atualiza o usuário no banco
            $user = User::updateOrCreate(
                ['email' => $email],
                ['name' => $nome, 'password' => Hash::make('password'), 'identificacao' => $matricula] // Senha aleatória
            );

            // Tenta autenticar o usuário
            Auth::login($user);
            Log::info('Usuário autenticado: ' . $user->email);
            Log::info('Usuário autenticado: ' . $user->name);

            // Redireciona para o painel ou área restrita
            return redirect()->intended(route('dashboard', absolute: false));

        }
    }

}
