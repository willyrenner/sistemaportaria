<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroSaida;
use App\Models\Aluno;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RegistroSaidaController extends Controller
{

    public function create()
    {
        return view('registro-saida.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula' => 'required|exists:alunos,matricula',
            'permissao' => 'required|date',
        ]);

        $funcionario = Auth::user();    //funcionario da coapac logad

        $aluno = Aluno::where('matricula', $request->matricula)->first();
        if (!$aluno) {
            return back()->with('error', 'Aluno não encontrado.');
        }


        $registro = new RegistroSaida();
        $registro->solicitacao = Carbon::now();
        $registro->permissao = $request->permissao;
        $registro->aluno_id = $aluno->id;
        $registro->funcionario_id = $funcionario->id;
        $registro->save();

        return redirect()->route('registro-saida.index')->with('success', 'Registro de saída criado com sucesso!');
    }

    public function index() //listagem de registros
    {
        $registros = RegistroSaida::with('aluno')->get();
        return view('registro-saida.index', compact('registros'));
    }

    
    public function controleDeSaida()   //view do porteiro
    {
        $registrosPendentes = RegistroSaida::with('aluno')
            ->whereNull('saida')
            ->get();

        return view('registro-saida.confirmar-pendentes', compact('registrosPendentes'));
    }

    public function confirmarSaida(Request $request, RegistroSaida $registro)
    {
        $request->validate([
            'saida' => 'required|date',
        ]);

        if (!$registro) {
            return redirect()->route('registro-saida.index')->with('error', 'Registro de saída não encontrado.');
        }

        // $registro->porteiro_id = id do porteiro logado
        $registro->saida = $request->saida; //atualiza data de saida
        $registro->save();

        return redirect()->route('registro-saida.index')->with('success', 'Saída confirmada com sucesso!');
    }
}
