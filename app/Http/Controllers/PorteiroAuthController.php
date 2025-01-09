<?php

namespace App\Http\Controllers;

use App\Models\Porteiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\RegistroSaida;
class PorteiroAuthController extends Controller
{
    /**
     * Exibe a página de login de porteiro.
     */
    public function loginForm()
    {
        return view('porteiro.login');
    }

    public function store(Request $request)
    {
        Log::info('Dados recebidos:', $request->all());
        // Validação dos dados do formulário
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:porteiros,email'],
            'cpf' => ['required', 'string', 'size:11', 'unique:porteiros,cpf'],
            'matricula' => ['required', 'string', 'max:20', 'unique:porteiros,matricula'],
            'turno' => ['required', 'string', 'max:20'],
        ]);

        // Registrar o porteiro
        Porteiro::create([
            'name' => $request->name,
            'email' => 'porteiro' . $request->cpf . '@example.com',
            'cpf' => $request->cpf,
            'matricula' => $request->matricula,
            'role' => 'porteiro', // Papel fixo
            'password' => Hash::make($request->cpf), // Senha baseada no CPF
            'turno' => $request->turno,
        ]);

        // Retornar mensagem de sucesso
        return redirect()->back()->with('success', 'Porteiro cadastrado com sucesso!');
    }

    /**
     * Realiza o login do porteiro.
     */
    public function login(Request $request)
    {
        // Adicionar log para verificar se o método foi chamado
        Log::info('Método de login chamado.');
        Log::info('Iniciando login...');
        Log::info('Método de requisição: ' . $request->method());
        Log::info('Dados recebidos: ', $request->all());

        // Validação dos dados do formulário
        $request->validate([
            'matricula' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Verificar se a matrícula existe
        $porteiro = Porteiro::where('matricula', $request->matricula)->first();

        // Log para verificar se a matrícula foi encontrada
        if ($porteiro) {
            Log::info('Porteiro encontrado: ' . $porteiro->matricula);
        } else {
            Log::info('Porteiro não encontrado para matrícula: ' . $request->matricula);
            return redirect()->back()->withErrors(['matricula' => 'Matrícula não encontrada.']);
        }

        // Verificar se a senha está correta
        if (!Hash::check($request->password, $porteiro->password)) {
            Log::warning('Senha incorreta para matrícula: ' . $request->matricula);
            throw ValidationException::withMessages([
                'matricula' => trans('auth.failed'),
            ]);
        }

        // Logar o porteiro
        Auth::guard('porteiro')->login($porteiro);

        // Log para confirmar o login
        Log::info('Porteiro logado com sucesso: ' . $porteiro->matricula);

        // Redirecionar para a dashboard do porteiro
        Log::info('Redirecionando para a dashboard do porteiro.');
        return redirect()->route('porteiro.dashboard');
    }

    /**
     * Realiza o logout do porteiro.
     */
    public function logout()
    {
        Auth::guard('porteiro')->logout();
        Log::info('Porteiro deslogado.');
        return redirect('/');
    }

    /**
     * Exibe o painel do porteiro.
     */
    public function dashboard()
    {
        $porteiro = Auth::guard('porteiro')->user();

        $movimentacoes = RegistroSaida::with('aluno') // Relacionando os alunos
            ->orderBy('solicitacao', 'desc') // Ordenando por data de solicitação
            ->limit(4)
            ->get(); // Obtendo todas as movimentações
        return view('porteiro.dashboard', compact('porteiro', 'movimentacoes'));
    }

    public function index()
    {
        // Encontre o porteiro pelo ID
        $porteiros = Porteiro::all();

        return view('porteiro.edit', compact('porteiros'));
    }


    public function update(Request $request, $id)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'cpf' => 'required|string|max:14', 
            'matricula' => 'required|string|max:50',
            'turno' => 'required|string|max:50',
        ]);

        // Encontrar o porteiro e atualizar os dados
        $porteiro = Porteiro::findOrFail($id);
        $porteiro->update($validatedData);

        return redirect()->route('porteiros.index')->with('success', 'Porteiro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        // Encontre o porteiro pelo ID
        $porteiro = Porteiro::findOrFail($id);

        // Exclui o porteiro
        $porteiro->delete();

        return redirect()->route('porteiros.index')->with('success', 'Porteiro excluído com sucesso!');
    }


}
