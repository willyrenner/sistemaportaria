<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start ">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Menu de Cursos') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded hover:bg-blue-600 inline-block">
                Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <button onclick="toggleForm()" class="w-full bg-blue-500 px-4 py-2 rounded mb-6 hover:bg-blue-600">Novo
                    Cadastro</button>

                <div id="cadastro-curso" class="bg-gray-800 p-4 rounded shadow-lg mb-6 hidden">
                    <h2 class="text-2xl font-semibold mb-4">CADASTRO DE CURSO</h2>

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

                    <form action="{{ route('cursos.store') }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <input type="text" name="curso" placeholder="NOME DO CURSO"
                                class="w-full px-3 py-2 rounded text-black" required>

                            <button type="submit" class="w-full bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                CADASTRAR
                            </button>
                        </div>
                    </form>
                </div>

                <div id="lista-cursos" class="bg-gray-800 p-4 rounded shadow-lg">
                    <h2 class="text-2xl font-semibold mb-4">Cursos Cadastrados</h2>
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left border">Nome do Curso</th>
                                <th class="px-4 py-2 text-left border">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cursos as $curso)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $curso->curso }}</td>
                                    <td class="px-4 py-2 border flex space-x-2 justify-start">
                                        <button onclick="toggleEditForm({{ $curso->id }})"
                                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Editar
                                        </button>

                                        <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este curso?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Formulário de Edição -->
                                <tr id="edit-form-{{ $curso->id }}" style="display: none;">
                                    <td colspan="2" class="px-4 py-2 border">
                                        <form action="{{ route('cursos.update', $curso->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="space-y-3">
                                                <input type="text" name="curso" value="{{ $curso->curso }} "
                                                    class="w-full px-3 py-2 rounded text-black" required>

                                                <button type="submit"
                                                    class="w-full bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                                    Atualizar
                                                </button>

                                                <button type="button" onclick="toggleEditForm({{ $curso->id }})"
                                                    class="w-full bg-gray-500 px-4 py-2 rounded hover:bg-gray-600">
                                                    Cancelar
                                                </button>
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
            const form = document.getElementById('cadastro-curso');
            form.classList.toggle('hidden');
        }

        function toggleEditForm(id) {
            var form = document.getElementById('edit-form-' + id);
            form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
</x-app-layout>