<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
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

    // public function index()
    // {

    //     $alunos = Aluno::all();
    //     $responsaveis = Responsavel::all();
    //     $cursos = Curso::all();


    //     return view('alunos.index', compact('alunos', 'responsaveis', 'cursos'));
    // }

    public function index(Request $request)
    {
        $query = Aluno::query();
        $responsaveis = Responsavel::all();
        $cursos = Curso::all();

        // Verifica se há um critério de busca
        if ($request->filled('buscar') && $request->filled('tipo')) {
            $tipo = $request->get('tipo');
            $buscar = $request->get('buscar');

            // Aplica o filtro com base no tipo selecionado
            if ($tipo === 'nome') {
                // Ajusta para buscar nomes que começam com o texto fornecido
                $query->where('nome', 'like', $buscar . '%');
            } elseif ($tipo === 'matricula') {
                // Deixe a lógica de matrícula como estava
                $query->where('matricula', 'like', '%' . $buscar . '%');
            }
        }

        $alunos = $query->get(); // Recupera os alunos filtrados ou todos

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

    public function import(Request $request)
    {
        $request->validate([
            'arquivo_excel' => 'required|file|mimes:xlsx,xls',
        ]);

        $arquivo = $request->file('arquivo_excel');

        try {
            // Carregar o arquivo Excel
            $spreadsheet = IOFactory::load($arquivo->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Loop para processar cada linha da planilha (pulando o cabeçalho)
            foreach ($rows as $index => $row) {
                if ($index === 0) {
                    // Pule a linha de cabeçalho
                    continue;
                }

                // Certifique-se de ajustar os índices do array conforme a estrutura do Excel
                Aluno::create([
                    'matricula' => $row[0],
                    'nome' => $row[1],
                    'email' => $row[2],
                    'telefone' => $row[3],
                    'data_nascimento' => $row[4],
                    'responsavel_id' => $row[5],
                    'curso_id' => $row[6],
                ]);
            }

            return redirect()->route('alunos.index')->with('success', 'Alunos importados com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao importar o arquivo: ' . $e->getMessage());
        }
    }
}
