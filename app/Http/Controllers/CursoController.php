<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;

class CursoController extends Controller
{
    public function create()
    {
        return view('curso.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'curso' => 'required|string|max:255|unique:cursos,curso',
        ]);

        Curso::create($validatedData);

        return redirect()->route('cursos.index')->with('success', 'Curso cadastrado com sucesso!');
    }

    public function index()
    {
        $cursos = Curso::all();

        return view('cursos.index', compact('cursos'));
    }

    public function edit($id)
    {
        $curso = Curso::findOrFail($id);

        return view('cursos.edit', compact('curso'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'curso' => 'required|string|max:255|unique:cursos,curso,' . $id,
        ]);

        $curso = Curso::findOrFail($id);
        $curso->update($validatedData);

        return redirect()->route('cursos.index')->with('success', 'Curso atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();

        return redirect()->route('cursos.index')->with('success', 'Curso excluído com sucesso!');
    }
}
