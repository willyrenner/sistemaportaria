<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroSaida;
use App\Models\Aluno;
use App\Models\CadastrarVisitante;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistroSaidaController extends Controller
{
    public function registros() // Listagem de registros
    {
        $registros = CadastrarVisitante::all();
        return view('registros.visitantes', compact('registros'));
    }

    public function index() // Listagem de registros
    {
        $registros = RegistroSaida::with('aluno', 'funcionario')->get();
        return view('registros.index', compact('registros'));
    }

    public function todosRegistros(Request $request)
    {
        // Query de registros de alunos
        $queryAlunos = RegistroSaida::with('aluno', 'funcionario')
            ->select(
                'id',
                'tipo',
                'motivo',
                'solicitacao as data',
                'aluno_id',
                'funcionario_id',
                DB::raw("'aluno' as categoria")
            );

        // Query de registros de visitantes
        $queryVisitantes = CadastrarVisitante::select(
            'id',
            'tipo',
            'motivo',
            'created_at as data',
            'nome',
            'cpf',
            DB::raw("'visitante' as categoria")
        );

        // Filtro pelo nome
        if ($request->filled('nome')) {
            $queryAlunos->whereHas('aluno', function ($query) use ($request) {
                $query->where('nome', 'like', '%' . $request->nome . '%');
            });

            $queryVisitantes->where('nome', 'like', '%' . $request->nome . '%');
        }

        // Mesclando registros e ordenando
        $registros = $queryAlunos->get()->merge($queryVisitantes->get())->sortByDesc('data');

        // Retornando a view com os registros
        return view('registros.todos', compact('registros'));
    }


    public function create()
    {
        return view('registros.create');
    }

    public function store(Request $request)
    {
        // Validar os dados recebidos
        $request->validate([
            'matricula' => 'required|string',
            'tipo' => 'required|string', // Adicione se entrada ou saída for relevante
            'saida' => 'nullable|date', // Pode ser nulo na criação
            'permissao' => 'nullable|boolean',
            'observacao_responsavel' => 'nullable|string',
            'motivo' => $request->tipo === 'saida' ? 'required|string' : 'nullable|string',
        ], [
            'motivo.required' => 'O campo motivo é obrigatório para solicitações de saída.',
        ]);

        // Buscar o aluno pela matrícula
        $aluno = Aluno::where('matricula', $request->matricula)->first();

        if (!$aluno) {
            return redirect()->back()->withErrors(['matricula' => 'Aluno não encontrado.']);
        }

        // Calcular a idade do aluno
        $data_nascimento = Carbon::parse($aluno->data_nascimento);
        $idade = Carbon::now()->diffInYears($data_nascimento); // Calcula a idade

        // Determinar a autorização
        $permissaoMensagem = '';
        $permissao = null;

        // Verificar se o tipo é "entrada"
        if ($request->tipo === 'entrada') {
            // Se for entrada, não exibe mensagem de autorização
            $permissao = 'Autorizada';
            $permissaoMensagem = '';
        } else {
            // Se for "saída", verificar a idade
            if ($idade >= 18) {
                // Se o aluno for maior de idade, autoriza automaticamente
                $permissao = 'Autorizada';
                $permissaoMensagem = 'Autorização confirmada - aluno maior de idade.';
            } else {
                // Se o aluno for menor de idade, precisa da autorização da secretaria
                $permissaoMensagem = 'Necessário autorizar na secretaria - aluno menor de idade.';
            }
        }

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
        RegistroSaida::create([
            'aluno_id' => $aluno->id,
            'funcionario_id' => $funcionarioId,
            'porteiro_id' => $porteiroId,
            'tipo' => $request->tipo, // 'entrada' ou 'saida'
            'permissao' => $permissao, // Se for maior de idade, terá a data e hora atual
            'saida' => $permissao ? Carbon::now() : null, // Registra a saída se maior de idade
            'motivo' => $request->motivo ?? null,
            'observacao_responsavel' => $request->observacao_responsavel ?? null,
            'solicitacao' => Carbon::now(),
        ]);

        // Retorna com a mensagem de autorização (se não for entrada)
        if ($request->tipo === 'saida') {
            return redirect()->back()->with('status', $permissaoMensagem);
        }

        // Se for entrada, não exibe mensagem
        return redirect()->back()->with('status', 'Entrada registrada com sucesso!');
    }

    public function autorizarSaidasMenores()
    {
        // Filtrar registros de saída pendentes de alunos menores de idade
        $registrosPendentes = RegistroSaida::with('aluno')
            ->whereNull('saida')
            ->whereHas('aluno', function ($query) {
                $query->whereDate('data_nascimento', '>', Carbon::now()->subYears(18));
            })
            ->get();

        return view('registros.autorizar-menores', compact('registrosPendentes'));
    }


    public function confirmarSaida(Request $request, RegistroSaida $registro)
    {
        if (!$registro) {
            return redirect()->route('autorizar-menores')->with('error', 'Registro de saída não encontrado.');
        }

        $validatedData = $request->validate([
            'observacao_responsavel' => 'nullable|string|max:255',
        ]);

        $funcionario_id = Auth::guard('web')->user()->id;

        // Atualiza a saída para o horário atual
        $registro->saida = Carbon::now();
        $registro->permissao = "Autorizada";
        $registro->observacao_responsavel = $validatedData['observacao_responsavel'] ?? null;
        $registro->funcionario_id = $funcionario_id;
        $registro->save();

        return redirect()->route('autorizar-menores')->with('success', 'Saída confirmada com sucesso!');
    }

    public function negarSaida(Request $request, RegistroSaida $registro)
    {
        if (!$registro) {
            return redirect()->route('autorizar-menores')->with('error', 'Registro de saída não encontrado.');
        }

        $validatedData = $request->validate([
            'observacao_responsavel' => 'nullable|string|max:255',
        ]);

        $funcionario_id = Auth::guard('web')->user()->id;

        // Atualiza a saída para o horário atual
        $registro->saida = Carbon::now();
        $registro->permissao = "Negada";
        $registro->observacao_responsavel = $validatedData['observacao_responsavel'] ?? null;
        $registro->funcionario_id = $funcionario_id;
        $registro->save();

        return redirect()->route('autorizar-menores')->with('success', 'Saída negada com sucesso!');
    }

    public function movimentacoes()
    {
        // Buscando as movimentações recentes (entrada ou saída), junto com o status de autorização
        $movimentacoes = RegistroSaida::with('aluno')
            ->orderBy('solicitacao', 'desc')
            ->limit(10)
            ->get();

        return $movimentacoes;
    }

}
