<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start ">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Menu de Alunos') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="bg-green-500 text-white px-4 py-2 mt-4 rounded hover:bg-green-600 inline-block">
                Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white text-white overflow-hidden shadow-lg sm:rounded-lg p-6">

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

                <div class="bg-white text-black p-4 rounded shadow-lg mb-6">
                    <h2 class="text-2xl font-semibold mb-4">Importar Alunos (Excel)</h2>
                    <form action="{{ route('alunos.import') }}" class="flex justify-center items-center" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="arquivo_excel" accept=".xlsx, .xls" class="block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0
                            file:font file:bg-green-500 file:text-white hover:file:bg-green-600" required>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Importar
                        </button>
                    </form>
                </div>

                <button onclick="toggleForm()"
                    class="w-full bg-green-500 px-4 py-2 rounded mb-6 hover:bg-green-600">Novo Cadastro</button>

                <div id="cadastro-aluno" class=" bg-white p-4 rounded shadow-lg mb-6 hidden">
                    <h2 class="text-2xl text-black font-semibold mb-4">CADASTRO DE ALUNO</h2>
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
                            <input type="date" name="data_nascimento" class="w-full px-3 py-2 rounded text-black"
                                required>
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

                <div id="lista-alunos" class="bg-white text-black p-4 rounded shadow-lg">
                    <h2 class="text-2xl font-semibold mb-4">Lista de Alunos</h2>
                    <div class="mb-4">
                        <form action="{{ route('alunos.index') }}" method="GET" class="flex items-center gap-2">
                            <select name="tipo" class="w-1/4 px-3 py-2 rounded text-black border" required>
                                <option value="nome" {{ request('tipo') == 'nome' ? 'selected' : '' }}>Nome</option>
                                <option value="matricula" {{ request('tipo') == 'matricula' ? 'selected' : '' }}>Matrícula
                                </option>
                            </select>
                            <input type="text" name="buscar" class="flex-1 rounded border-gray-500"
                                oninput="handleInputChange(this)" placeholder="Digite aqui ..."
                                value="{{ request('buscar') }}" class="w-2/4 px-3 py-2 rounded text-black border">
                            <button type="submit"
                                class="uppercase bg-blue-500 px-4 py-2 rounded text-white hover:bg-blue-600">
                                Buscar
                            </button>
                        </form>
                    </div>
                    <table class="table-auto w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-green-600 text-white">
                                <th class="px-4 py-2 text-left border">Matrícula</th>
                                <th class="px-4 py-2 text-left border">Nome</th>
                                <th class="px-4 py-2 text-left border">Email</th>
                                <th class="px-4 py-2 text-left border w-[320px]">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alunos as $aluno)
                                <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                    <td class="px-4 py-2 border">{{ $aluno->matricula }}</td>
                                    <td class="px-4 py-2 border">{{ $aluno->nome }}</td>
                                    <td class="px-4 py-2 border">{{ $aluno->email }}</td>
                                    <td class="px-4 py-2 border flex justify-around w-[320px]">
                                        <button onclick="showEditForm({{ $aluno->id }})"
                                            class="bg-yellow-500 px-4 py-2 rounded text-white hover:bg-yellow-600">Editar</button>
                                        <form action="{{ route('alunos.destroy', $aluno->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="event.preventDefault(); confirmDelete(this, '{{ $aluno->nome}}', '{{$aluno->matricula}}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 px-4 py-2 rounded text-white hover:bg-red-600">Excluir</button>
                                        </form>
                                        <button onclick="copyId({{ $aluno->matricula }})"
                                            class="bg-blue-500 px-4 py-2 rounded text-white hover:bg-blue-600">Matrícula</button>

                                    </td>
                                </tr>
                                <div id="error-message" class="bg-green-500 text-white p-4 rounded mb-4 hidden"></div>

                                <tr id="edit-form-{{ $aluno->id }}" class="hidden">
                                    <td colspan="4">
                                        <form action="{{ route('alunos.update', $aluno->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="bg-white p-4 rounded shadow-lg">
                                                <div class="space-y-3">
                                                    <input type="text" name="matricula" value="{{ $aluno->matricula }}"
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                    <input type="text" name="nome" value="{{ $aluno->nome }}"
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                    <input type="email" name="email" value="{{ $aluno->email }}"
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                    <input type="text" name="telefone" value="{{ $aluno->telefone }}"
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                    <input type="date" name="data_nascimento"
                                                        value="{{ $aluno->data_nascimento }}"
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                    <select name="responsavel_id"
                                                        class="w-full px-3 py-2 rounded text-black" required>
                                                        <option value="">Selecione o Responsável</option>
                                                        @foreach($responsaveis as $responsavel)
                                                            <option value="{{ $responsavel->id }}" {{ $aluno->responsavel_id == $responsavel->id ? 'selected' : '' }}>
                                                                {{ $responsavel->nome }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <select name="curso_id" class="w-full px-3 py-2 rounded text-black"
                                                        required>
                                                        <option value="">Selecione o Curso</option>
                                                        @foreach($cursos as $curso)
                                                            <option value="{{ $curso->id }}" {{ $aluno->curso_id == $curso->id ? 'selected' : '' }}> {{ $curso->curso }} </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit"
                                                        class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
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
        <!-- Modal de Confirmação -->
        <div id="confirmModal"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center backdrop-filter backdrop-blur-sm">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-center" id="confirmMessage">Confirmando ...</h2>
                <div class="flex justify-between">
                    <button id="confirmOk" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
                        Sim
                    </button>
                    <button id="confirmCancel" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-500">
                        Não
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/alunos.js') }}"></script>
</x-app-layout>
