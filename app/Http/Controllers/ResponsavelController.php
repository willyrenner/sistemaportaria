<?php

namespace App\Http\Controllers;

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
}
