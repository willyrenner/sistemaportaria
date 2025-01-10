<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Porteiros Cadastrados') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Exibição de Mensagens -->
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

                <!-- Botão para Exibir o Formulário de Cadastro -->
                <button onclick="toggleCadastroForm()" class="w-full bg-blue-500 px-4 py-2 rounded mb-6 hover:bg-blue-600">Novo Cadastro</button>

                <!-- Formulário de Cadastro (Iniciado como oculto) -->
                <div id="cadastro-porteiros" class="bg-gray-800 p-4 rounded shadow-lg mb-6 hidden">
                    <h2 class="text-2xl font-semibold mb-4">CADASTRO DE PORTEIROS</h2>
                    <form action="{{ route('porteiros.store') }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <!-- Campo Nome -->
                            <input type="text" name="name" placeholder="NOME" class="w-full px-3 py-2 rounded text-black" required>
                            <input type="hidden" name="email" value="">
                            <!-- Campo CPF -->
                            <input type="text" name="cpf" placeholder="CPF" class="w-full px-3 py-2 rounded text-black" required>
                            <!-- Campo Matrícula -->
                            <input type="text" name="matricula" placeholder="MATRÍCULA" class="w-full px-3 py-2 rounded text-black" required>
                            <!-- Campos Fixos -->
                            <input type="hidden" name="role" value="porteiro">
                            <!-- Campo Turnos -->
                            <div>
                                <h4 class="font-semibold text-lg mb-2">TURNO</h4>
                                <select name="turno" class="w-full px-3 py-2 rounded text-black">
                                    <option value="">Selecione o turno</option>
                                    <option value="matutino">MATUTINO</option>
                                    <option value="vespertino">VESPERTINO</option>
                                    <option value="noturno">NOTURNO</option>
                                </select>
                            </div>
                            <!-- Botão de Enviar -->
                            <button type="submit" class="w-full bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                CADASTRAR
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabela de Porteiros -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-800 border border-gray-700">
                        <thead>
                            <tr class="bg-gray-700">
                                <th class="px-4 py-2 text-left border-b">Nome</th>
                                <th class="px-4 py-2 text-left border-b">Email</th>
                                <th class="px-4 py-2 text-left border-b">CPF</th>
                                <th class="px-4 py-2 text-left border-b">Matrícula</th>
                                <th class="px-4 py-2 text-left border-b">Turno</th>
                                <th class="px-4 py-2 text-left border-b">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($porteiros as $porteiro)
                                <tr class="hover:bg-gray-700 border-b">
                                    <td class="px-4 py-2">{{ $porteiro->name }}</td>
                                    <td class="px-4 py-2">{{ $porteiro->email }}</td>
                                    <td class="px-4 py-2">{{ $porteiro->cpf }}</td>
                                    <td class="px-4 py-2">{{ $porteiro->matricula }}</td>
                                    <td class="px-4 py-2">{{ $porteiro->turno ?? 'Nenhum' }}</td>
                                    <td class="px-4 py-2 flex space-x-2 justify-start">
                                        <!-- Botão Editar -->
                                        <button onclick="toggleEditForm({{ $porteiro->id }})" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Editar
                                        </button>

                                        <!-- Formulário para Excluir -->
                                        <form action="{{ route('porteiros.destroy', $porteiro->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este porteiro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Formulário de Edição do Porteiro (Exibido abaixo da linha do Porteiro) -->
                                <tr id="edit-form-{{ $porteiro->id }}" class="hidden">
                                    <td colspan="6">
                                        <form action="{{ route('porteiros.update', $porteiro->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="space-y-4">
                                                <input type="text" name="name" value="{{ $porteiro->name }}" class="w-full px-4 py-2 rounded text-black bg-white" required>
                                                <input type="email" name="email" value="{{ $porteiro->email }}" class="w-full px-4 py-2 rounded text-black bg-white">
                                                <input type="text" name="cpf" value="{{ $porteiro->cpf }}" class="w-full px-4 py-2 rounded text-black bg-white" required>
                                                <input type="text" name="matricula" value="{{ $porteiro->matricula }}" class="w-full px-4 py-2 rounded text-black bg-white" required>

                                                <!-- Turno - Select Box com valores de turno -->
                                                <div class="space-y-2">
                                                    <label class="block">
                                                        <input type="radio" name="turno" value="matutino"
                                                            {{ $porteiro->turno === 'matutino' ? 'checked' : '' }} class="mr-2"> MATUTINO
                                                    </label>
                                                    <label class="block">
                                                        <input type="radio" name="turno" value="vespertino"
                                                            {{ $porteiro->turno === 'vespertino' ? 'checked' : '' }} class="mr-2"> VESPERTINO
                                                    </label>
                                                    <label class="block">
                                                        <input type="radio" name="turno" value="noturno"
                                                            {{ $porteiro->turno === 'noturno' ? 'checked' : '' }} class="mr-2"> NOTURNO
                                                    </label>
                                                </div>

                                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Salvar</button>
                                                <button type="button" onclick="toggleEditForm({{ $porteiro->id }})"
                                                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</button>
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
        // Função para alternar a exibição do formulário de edição
        function toggleEditForm(id) {
            var form = document.getElementById('edit-form-' + id);
            form.classList.toggle('hidden');
        }

        // Função para exibir o formulário de cadastro
        function toggleCadastroForm() {
            var form = document.getElementById('cadastro-porteiros');
            form.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
