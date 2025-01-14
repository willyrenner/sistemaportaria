<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start ">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Menu de Responsáveis') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="bg-green-500 text-white px-4 py-2 mt-4 rounded hover:bg-green-600 inline-block">
                Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 min-h-screen">
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
                <button onclick="toggleForm()"
                    class="w-full bg-green-500 px-4 py-2 rounded mb-6 hover:bg-green-600">Novo
                    Cadastro</button>

                <div id="cadastro-responsavel" class="text-black p-4 rounded shadow-lg mb-6 hidden">
                    <h2 class="text-2xl text-black font-semibold mb-4">CADASTRO DE RESPONSÁVEL</h2>



                    <form action="{{ route('responsaveis.store') }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <input type="text" name="nome" placeholder="NOME DO RESPONSÁVEL"
                                class="w-full px-3 py-2 rounded text-black" required>

                            <input type="text" name="telefone" placeholder="TELEFONE DO RESPONSÁVEL"
                                class="w-full px-3 py-2 rounded text-black" required>

                            <button type="submit"
                                class="w-full text-white bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                CADASTRAR
                            </button>
                        </div>
                    </form>
                </div>

                <div id="lista-responsaveis" class="bg-white text-black p-4 rounded shadow-lg">
                    <h2 class="text-2xl font-semibold mb-4">Responsáveis Cadastrados</h2>
                    <table class="table-auto w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-green-600 text-white">
                                <th class="px-4 py-2 text-left border">Nome</th>
                                <th class="px-4 py-2 text-left border">Telefone</th>
                                <th class="px-4 py-2 text-left border">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($responsaveis as $responsavel)
                                <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                    <td class="px-4 py-2 border">{{ $responsavel->nome }}</td>
                                    <td class="px-4 py-2 border">{{ $responsavel->telefone }}</td>
                                    <td class="px-4 py-2 border flex space-x-2 justify-start">
                                        <button onclick="toggleEditForm({{ $responsavel->id }})"
                                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Editar
                                        </button>

                                        <form action="{{ route('responsaveis.destroy', $responsavel->id) }}" method="POST"
                                            class="inline-block ml-2"
                                            onsubmit="event.preventDefault(); confirmDelete(this, '{{ $responsavel->nome }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 px-4 py-2 rounded text-white hover:bg-red-600">Excluir</button>
                                        </form>
                                    </td>
                                </tr>

                                <tr id="edit-form-{{ $responsavel->id }}" style="display: none;">
                                    <td colspan="3" class="px-4 py-2 border">
                                        <form action="{{ route('responsaveis.update', $responsavel->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="space-y-3">
                                                <input type="text" name="nome" value="{{ $responsavel->nome }}"
                                                    class="w-full px-3 py-2 rounded text-black" required>

                                                <input type="text" name="telefone" value="{{ $responsavel->telefone }}"
                                                    class="w-full px-3 py-2 rounded text-black" required>

                                                <button type="submit"
                                                    class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                                    Atualizar
                                                </button>

                                                <button type="button" onclick="toggleEditForm({{ $responsavel->id }})"
                                                    class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
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
                <div id="confirmModal"
                    class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden backdrop-filter backdrop-blur-sm">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                        <h2 class="text-xl text-black font-bold mb-4 text-center" id="confirmMessage">Confirmando...
                        </h2>
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
        </div>
    </div>

    <script>
        let deleteForm; // Variável para armazenar o formulário de exclusão

        // Exibe o modal de confirmação
        function confirmDelete(form, responsavelNome) {
            deleteForm = form; // Salva o formulário que será enviado
            const modal = document.getElementById('confirmModal');
            const message = document.getElementById('confirmMessage');
            message.textContent = `Tem certeza de que deseja excluir o responsável ${responsavelNome}?`;
            modal.classList.remove('hidden');
        }

        // Fecha o modal sem realizar a ação
        document.getElementById('confirmCancel').addEventListener('click', function () {
            const modal = document.getElementById('confirmModal');
            modal.classList.add('hidden');
        });

        // Confirma a exclusão e envia o formulário
        document.getElementById('confirmOk').addEventListener('click', function () {
            if (deleteForm) {
                deleteForm.submit(); // Submete o formulário de exclusão
            }
        });


        function toggleForm() {
            const form = document.getElementById('cadastro-responsavel');
            form.classList.toggle('hidden');
        }

        function toggleEditForm(id) {
            var form = document.getElementById('edit-form-' + id);
            form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
</x-app-layout>