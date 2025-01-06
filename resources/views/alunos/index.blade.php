<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Menu de Alunos') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-500 text-white p-4 rounded mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button onclick="toggleForm()" class="w-full bg-blue-500 px-4 py-2 rounded mb-6 hover:bg-blue-600">Novo Cadastro</button>

                <div id="cadastro-aluno" class="bg-gray-800 p-4 rounded shadow-lg mb-6 hidden">
                    <h2 class="text-2xl font-semibold mb-4">CADASTRO DE ALUNO</h2>
                    <form action="{{ route('alunos.store') }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <input type="text" name="matricula" placeholder="MATRÍCULA" 
                                class="w-full px-3 py-2 rounded text-black" required>
                            <input type="text" name="nome" placeholder="NOME" 
                                class="w-full px-3 py-2 rounded text-black" required>
                            <input type="email" name="email" placeholder="EMAIL" 
                                class="w-full px-3 py-2 rounded text-black" required>
                            <input type="text" name="telefone" placeholder="TELEFONE" 
                                class="w-full px-3 py-2 rounded text-black" required>
                            <input type="date" name="data_nascimento" 
                                class="w-full px-3 py-2 rounded text-black" required>
                            <select name="responsavel_id" class="w-full px-3 py-2 rounded text-black" required>
                                <option value="">Selecione o Responsável</option>
                                @foreach($responsaveis as $responsavel)
                                    <option value="{{ $responsavel->id }}">{{ $responsavel->nome }}</option>
                                @endforeach
                            </select>
                            <select name="curso_id" class="w-full px-3 py-2 rounded text-black" required>
                                <option value="">Selecione o Curso</option>
                                @foreach($cursos as $curso)
                                    <option class="text-black" value="{{ $curso->id }}">{{ $curso->curso }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                CADASTRAR
                            </button>
                        </div>
                    </form>
                </div>

                <div id="lista-alunos" class="bg-gray-800 p-4 rounded shadow-lg">
                    <h2 class="text-2xl font-semibold mb-4">Lista de Alunos</h2>
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left border">Matrícula</th>
                                <th class="px-4 py-2 text-left border">Nome</th>
                                <th class="px-4 py-2 text-left border">Email</th>
                                <th class="px-4 py-2 text-left border">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alunos as $aluno)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $aluno->matricula }}</td>
                                    <td class="px-4 py-2 border">{{ $aluno->nome }}</td>
                                    <td class="px-4 py-2 border">{{ $aluno->email }}</td>
                                    <td class="px-4 py-2 border">
                                        <button onclick="showEditForm({{ $aluno->id }})"
                                            class="bg-yellow-500 px-4 py-2 rounded text-white hover:bg-yellow-600">Editar</button>
                                        <form action="{{ route('alunos.destroy', $aluno->id) }}" method="POST" class="inline-block ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 px-4 py-2 rounded text-white hover:bg-red-600">Excluir</button>
                                        </form>
                                    </td>
                                </tr>

                                <tr id="edit-form-{{ $aluno->id }}" class="hidden">
                                    <td colspan="4">
                                        <form action="{{ route('alunos.update', $aluno->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="bg-gray-800 p-4 rounded shadow-lg">
                                                <div class="space-y-3">
                                                    <input type="text" name="matricula" value="{{ $aluno->matricula }}" 
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                    <input type="text" name="nome" value="{{ $aluno->nome }}" 
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                    <input type="email" name="email" value="{{ $aluno->email }}" 
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                    <input type="text" name="telefone" value="{{ $aluno->telefone }}" 
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                    <input type="date" name="data_nascimento" value="{{ $aluno->data_nascimento }}" 
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                    <select name="responsavel_id" class="w-full px-3 py-2 rounded text-black" required>
                                                        <option value="">Selecione o Responsável</option>
                                                        @foreach($responsaveis as $responsavel)
                                                            <option value="{{ $responsavel->id }}" 
                                                                {{ $aluno->responsavel_id == $responsavel->id ? 'selected' : '' }}>
                                                                {{ $responsavel->nome }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <select name="curso_id" class="w-full px-3 py-2 rounded text-black" required>
                                                        <option value="">Selecione o Curso</option>
                                                        @foreach($cursos as $curso)
                                                            <option value="{{ $curso->id }}" 
                                                                {{ $aluno->curso_id == $curso->id ? 'selected' : '' }}>
                                                                {{ $curso->curso }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="w-full bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                                        Atualizar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>

        function toggleForm() {
            const form = document.getElementById('cadastro-aluno');
            form.classList.toggle('hidden');
        }

        function showEditForm(id) {
            const form = document.getElementById(`edit-form-${id}`);
            form.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
