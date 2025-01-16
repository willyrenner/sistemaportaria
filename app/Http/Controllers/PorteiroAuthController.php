<?php

namespace App\Http\Controllers;

use App\Models\Porteiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\RegistroSaida;
use App\Models\CadastrarVisitante;
use App\Models\Aluno;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class PorteiroAuthController extends Controller
{

    

    public function registrosVisitantes() // Listagem de registros
    {
        $porteiro = Auth::guard('porteiro')->user();
        $registros = CadastrarVisitante::all();
        return view('porteiro.visitantes', compact('registros', 'porteiro'));
    }

    public function registrosAlunos() // Listagem de registros
    {
        $porteiro = Auth::guard('porteiro')->user();
        $registros = RegistroSaida::with('aluno')->get();
        return view('porteiro.index', compact('registros', 'porteiro'));
    }

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
        ], [
            'cpf.size' => 'O CPF deve ter exatamente 11 caracteres.',
            'cpf.unique' => 'O CPF já existe no sistema.',
            'turno.required' => 'O turno é obrigatório',
            'matricula.unique' => 'A matrícula já existe no sistema',
        ]);

        // Registrar o porteiro
        Porteiro::create([
            'name' => $request->name,
            'email' => 'porteiro' . $request->cpf . '@example.com',
            'cpf' => $request->cpf,
            'matricula' => $request->matricula,
            'role' => 'porteiro', 
            'password' => Hash::make($request->cpf),
            'turno' => $request->turno,
            'password_reset_required' => true,
        ]);

        // Retornar mensagem de sucesso
        return redirect()->back()->with('success', 'Porteiro cadastrado com sucesso!');
    }

    public function cadastrarVisitante(Request $request)
    {
        // Validar os dados recebidos
        $request->validate([
            'nome' => 'required|string',
            'cpf' => 'required|string|size:11',
            'tipo' => 'required|string', // Adicione se entrada ou saída for relevante
            'saida' => 'nullable|date', // Pode ser nulo na criação
            'motivo' => $request->tipo === 'entrada' ? 'required|string' : 'nullable|string',
        ], [
            'cpf.size' => 'O CPF deve ter exatamente 11 caracteres.',
            'motivo.required' => 'O campo motivo é obrigatório para solicitações de entrada.',
        ]);

        // Determinar os IDs de porteiro ou funcionário
        $porteiroId = null;
        $funcionarioId = null;

        if (Auth::guard('porteiro')->check()) {
            // Se o porteiro estiver autenticado
            $porteiroId = Auth::guard('porteiro')->user()->id;
        } elseif (Auth::guard('web')->check()) {
            // Se o funcionário da secretaria estiver autenticado
            $funcionarioId = Auth::guard('web')->user()->id;
        } else {
            // Nenhum usuário autenticado
            return redirect()->route('login')->withErrors(['error' => 'Usuário não autenticado.']);
        }

        // Criar o registro de saída
        CadastrarVisitante::create([

            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'tipo' => $request->tipo,
            'motivo' => $request->motivo,
            'saida' => null,
            'funcionario_id' => $funcionarioId,
            'porteiro_id' => $porteiroId,

        ]);

        if ($request->tipo === 'saida') {
            return redirect()->back()->with('status_visitante', value: 'Saída registrada com sucesso!');
        }

        return redirect()->back()->with('status_visitante', 'Entrada registrada com sucesso!');
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
            return redirect()->back()->withErrors('Senha Incorreta');
        }

        // Logar o porteiro
        Auth::guard('porteiro')->login($porteiro);

        // Log para confirmar o login
        Log::info('Porteiro logado com sucesso: ' . $porteiro->matricula);


        if ($porteiro->password_reset_required) {
            Log::info('Redirecionando para atualizer senha do porteiro.');
            return redirect()->route('porteiro.dashboard');
        } else {
            Log::info('Redirecionando para a dashboard do porteiro.');
            return redirect()->route('porteiro.dashboard');
        }

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
            ->limit(15)
            ->get(); // Obtendo todas as movimentações

        $registrosPendentes = CadastrarVisitante::whereNull('saida')
            ->limit(5)
            ->get();

        return view('porteiro.dashboard', compact('porteiro', 'movimentacoes', 'registrosPendentes'));
    }

    public function confirmarSaidaVisitante($id)
    {
        try {
            // Localiza o visitante pelo ID
            $visitante = CadastrarVisitante::findOrFail($id);

            // Verifica se o visitante já possui uma saída registrada
            if ($visitante->saida) {
                return redirect()->route('porteiro.dashboard')->withErrors('Saída do visitante já foi registrada anteriormente.');
            }

            // Registra a data e hora da saída
            $visitante->saida = Carbon::now();
            $visitante->save();

            return redirect()->route('porteiro.dashboard')->with('status_visitante_saida', 'Saída do visitante confirmada com sucesso!');
        } catch (ModelNotFoundException $e) {
            // Caso o visitante não seja encontrado
            Log::error("Erro ao confirmar saída: Visitante não encontrado (ID: $id).", ['exception' => $e]);
            return redirect()->route('porteiro.dashboard')->withErrors('Visitante não encontrado.');
        } catch (Exception $e) {
            // Tratamento de erros gerais
            Log::error("Erro inesperado ao confirmar saída do visitante (ID: $id).", ['exception' => $e]);
            return redirect()->route('porteiro.dashboard')->withErrors('Ocorreu um erro ao confirmar a saída do visitante. Por favor, tente novamente.');
        }
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

    public function resetPassword($id)
    {
        // Encontrar o porteiro e atualizar os dados
        $porteiro = Porteiro::findOrFail($id);
        $porteiro->password = Hash::make($porteiro->cpf);
        $porteiro->password_reset_required = 1;
        $porteiro->save();

        return redirect()->route('porteiros.index')->with('success', 'Senha do porteiro resetada com sucesso!');
    }

    public function destroy($id)
    {
        // Encontre o porteiro pelo ID
        $porteiro = Porteiro::findOrFail($id);

        // Exclui o porteiro
        $porteiro->delete();

        return redirect()->route('porteiros.index')->with('success', 'Porteiro excluído com sucesso!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.min' => 'A senha deve ter 8 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
        ]);

        $porteiro = Auth::guard('porteiro')->user();
        $porteiro->password = Hash::make($request->password);
        $porteiro->password_reset_required = 0;
        $porteiro->save();

        return redirect()->route('porteiro.dashboard')->with('success', 'Senha atualizada com sucesso!');
    }

    public function buscarAluno($matricula)
    {
        // Tenta buscar o aluno pela matrícula
        $aluno = Aluno::where('matricula', $matricula)->with(['responsavel', 'curso'])->first();

        // Verifica se encontrou o aluno
        if ($aluno) {
            // Retorna os dados do aluno como JSON
            $idade = Carbon::parse($aluno->data_nascimento)->age;
            return response()->json([
                'nome' => $aluno->nome,
                'matricula' => $aluno->matricula,
                'idade' => $idade,
                'responsavel' => $aluno->responsavel->nome,
                'curso' => $aluno->curso->curso,
            ]);
        } else {
            // Caso não encontre o aluno, retorna erro 404
            return response()->json(['message' => 'Aluno não encontrado.'], 404);
        }
    }



}
