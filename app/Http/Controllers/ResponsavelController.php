<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use App\Models\Responsavel;

class ResponsavelController extends Controller
{
    public function create()
    {
        return view('responsaveis.cadastrar');
    }

    public function index()
    {
        $responsaveis = Responsavel::all();
        return view('responsaveis.index', compact('responsaveis'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255|unique:responsaveis,nome',
            'telefone' => 'required|string|max:255|unique:responsaveis,telefone',
        ]);

        Responsavel::create($validatedData);

        return redirect()->route('responsaveis.index')->with('success', 'Responsável cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $responsavel = Responsavel::findOrFail($id);
        return view('responsaveis.edit', compact('responsavel'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255|unique:responsaveis,nome,' . $id,
            'telefone' => 'required|string|max:255|unique:responsaveis,telefone,' . $id,
        ]);

        $responsavel = Responsavel::findOrFail($id);
        $responsavel->update($validatedData);

        return redirect()->route('responsaveis.index')->with('success', 'Responsável atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $responsavel = Responsavel::findOrFail($id);
        $responsavel->delete();

        return redirect()->route('responsaveis.index')->with('success', 'Responsável excluído com sucesso!');
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
                Responsavel::create([
                    'nome' => $row[0],
                    'telefone' => $row[1],
                ]);
            }

            return redirect()->route('responsaveis.index')->with('success', 'Responsaveis importados com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao importar o arquivo: ' . $e->getMessage());
        }
    }
}
