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
        }

        try {
            $client = new Client();
            $response = $client->request('GET', 'https://suap.ifrn.edu.br/api/eu/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $nome = $data['nome_usual'] ?? 'Nome não disponível';
            $email = $data['email'] ?? 'E-mail não disponível';
            $matricula = $data['identificacao'] ?? 'identificacao não disponível';

            // Verifica o tipo de usuário
            // $tipo_usuario = strtolower($data['tipo_usuario'] ?? '');
            // $tipo_usuario_aluno = 'aluno';

            // if ($tipo_usuario === $tipo_usuario_aluno) {
            //     // Retorna uma resposta com erro e status HTTP 403
            //     return response()->json(['error' => 'Esse usuário não pode acessar o sistema!'], 403);
            // }

            // Cria ou atualiza o usuário no banco
            $user = User::updateOrCreate(
                ['email' => $email],
                ['name' => $nome, 'password' => Hash::make('password'), 'identificacao' => $matricula]
            );

            // Tenta autenticar o usuário
            Auth::login($user);
            Log::info('Usuário autenticado: ' . $user->email);
            Log::info('Usuário autenticado: ' . $user->name);

            // Redireciona para o painel ou área restrita
            return redirect('dashboard');
        } catch (\Exception $e) {
            Log::error('Erro ao processar o callback: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao processar a solicitação.'], 500);
        }
    }

}
