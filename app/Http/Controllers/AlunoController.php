<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Responsavel;
use App\Models\Curso;

class AlunoController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'matricula' => 'required|unique:alunos,matricula|max:255',
            'nome' => 'required|max:255',
            'email' => 'required|email|unique:alunos,email',
            'telefone' => 'required|max:15',
            'data_nascimento' => 'required|date',
            'responsavel_id' => 'required|exists:responsaveis,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        Aluno::create($validatedData);

        return redirect()->route('alunos.index')->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function index()
    {

        $alunos = Aluno::all();
        $responsaveis = Responsavel::all();
        $cursos = Curso::all();


        return view('alunos.index', compact('alunos', 'responsaveis', 'cursos'));
    }

    public function edit($id)
    {

        $aluno = Aluno::findOrFail($id);
        $responsaveis = Responsavel::all();
        $cursos = Curso::all();

        return view('alunos.index', compact('aluno', 'responsaveis', 'cursos'));
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'matricula' => 'required|max:255|unique:alunos,matricula,' . $id,
            'nome' => 'required|max:255',
            'email' => 'required|email|unique:alunos,email,' . $id,
            'telefone' => 'required|max:15',
            'data_nascimento' => 'required|date',
            'responsavel_id' => 'required|exists:responsaveis,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);


        $aluno = Aluno::findOrFail($id);
        $aluno->update($validatedData);

        return redirect()->route('alunos.index')->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $aluno = Aluno::findOrFail($id);
        $aluno->delete();

        return redirect()->route('alunos.index')->with('success', 'Aluno excluído com sucesso!');
    }
}
