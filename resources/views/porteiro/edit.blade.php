<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Menu de Porteiros') }}
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

                <!-- Botão para Exibir o Formulário de Cadastro -->
                <button onclick="toggleCadastroForm()"
                    class="w-full bg-green-500 px-4 py-2 rounded mb-6 hover:bg-green-600">Novo Cadastro</button>

                <div id="lista-cursos" class="bg-white text-black p-4 rounded shadow-lg">
                    <h2 class="text-2xl font-semibold text-black mb-4">Porteiros Cadastrados</h2>
                    <!-- Formulário de Cadastro (Iniciado como oculto) -->
                    <div id="cadastro-porteiros" class="bg-white p-4 rounded shadow-lg mb-6 hidden">
                        <h2 class="text-2xl text-black font-semibold mb-4">CADASTRO DE PORTEIROS</h2>
                        <form action="{{ route('porteiros.store') }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <!-- Campo Nome -->
                                <input type="text" name="name" placeholder="NOME"
                                    class="w-full px-3 py-2 rounded text-black" required>
                                <input type="hidden" name="email" value="">
                                <!-- Campo CPF -->
                                <input type="text" name="cpf" placeholder="CPF"
                                    class="w-full px-3 py-2 rounded text-black" required>
                                <!-- Campo Matrícula -->
                                <input type="text" name="matricula" placeholder="MATRÍCULA"
                                    class="w-full px-3 py-2 rounded text-black" required>
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
                                <button type="submit"
                                    class="w-full text-white bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                    CADASTRAR
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela de Porteiros -->
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-green-600 text-white">
                                    <th class="px-4 py-2 text-left border">Nome</th>
                                    <th class="px-4 py-2 text-left border">Email</th>
                                    <th class="px-4 py-2 text-left border">CPF</th>
                                    <th class="px-4 py-2 text-left border">Matrícula</th>
                                    <th class="px-4 py-2 text-left border">Turno</th>
                                    <th class="px-4 py-2 text-left border">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($porteiros as $porteiro)
                                    <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                        <td class="px-4 py-2 text-black border">{{ $porteiro->name }}</td>
                                        <td class="px-4 py-2 text-black border">{{ $porteiro->email }}</td>
                                        <td class="px-4 py-2 text-black border">{{ $porteiro->cpf }}</td>
                                        <td class="px-4 py-2 text-black border">{{ $porteiro->matricula }}</td>
                                        <td class="px-4 py-2 text-black border">{{ $porteiro->turno ?? 'Nenhum' }}</td>
                                        <td class="px-4 py-2 flex space-x-2 justify-start border">
                                            <!-- Botão Editar -->
                                            <button onclick="toggleEditForm({{ $porteiro->id }})"
                                                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Editar
                                            </button>

                                            <!-- Formulário para Excluir -->
                                            <form action="{{ route('porteiros.destroy', $porteiro->id) }}" method="POST"
                                                class="inline-block ml-2"
                                                onsubmit="event.preventDefault(); confirmDelete(this, '{{ $porteiro->name }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 px-4 py-2 rounded text-white hover:bg-red-600">Excluir</button>
                                            </form>

                                            <form action="{{ route('porteiros.resetpassword', $porteiro->id) }}"
                                                method="POST" class="inline-block ml-2"
                                                onsubmit="event.preventDefault(); confirmPassword(this, '{{ $porteiro->name }}');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Resetar
                                                    Senha</button>
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
                                                    <input type="text" name="name" value="{{ $porteiro->name }}"
                                                        class="w-full px-4 py-2 rounded text-black bg-white" required>
                                                    <input type="email" name="email" value="{{ $porteiro->email }}"
                                                        class="w-full px-4 py-2 rounded text-black bg-white">
                                                    <input type="number" name="cpf" value="{{ $porteiro->cpf }}"
                                                        class="w-full px-4 py-2 rounded text-black bg-white"
                                                        oninput="limitInputLength(this, 11)" required>
                                                    <input type="number" name="matricula" value="{{ $porteiro->matricula }}"
                                                        class="w-full px-4 py-2 rounded text-black bg-white"
                                                        oninput="limitInputLength(this, 14)" required>

                                                    <!-- Turno - Select Box com valores de turno -->
                                                    <div class="space-y-2 text-black">
                                                        <label class="block">
                                                            <input type="radio" name="turno" value="matutino" {{ $porteiro->turno === 'matutino' ? 'checked' : '' }}
                                                                class="mr-2">
                                                            MATUTINO
                                                        </label>
                                                        <label class="block">
                                                            <input type="radio" name="turno" value="vespertino" {{ $porteiro->turno === 'vespertino' ? 'checked' : '' }}
                                                                class="mr-2"> VESPERTINO
                                                        </label>
                                                        <label class="block">
                                                            <input type="radio" name="turno" value="noturno" {{ $porteiro->turno === 'noturno' ? 'checked' : '' }}
                                                                class="mr-2">
                                                            NOTURNO
                                                        </label>
                                                    </div>

                                                    <button type="submit"
                                                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Salvar</button>
                                                    <button type="button" onclick="toggleEditForm({{ $porteiro->id }})"
                                                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Cancelar</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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

        <script>
            function limitInputLength(input, maxLength) {
                if (input.value.length > maxLength) {
                    input.value = input.value.slice(0, maxLength); // Limita os caracteres
                }
            }
        </script>

        <script>
            let deleteForm;

            function confirmDelete(form, porteiroNome) {
                deleteForm = form; // Salva o formulário que será enviado
                const modal = document.getElementById('confirmModal');
                const message = document.getElementById('confirmMessage');
                message.textContent = `Tem certeza de que deseja excluir o porteiro ${porteiroNome}?`;
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

            let resetPassword;

            function confirmPassword(form, porteiroNome) {
                resetPassword = form; // Salva o formulário que será enviado
                const modal = document.getElementById('confirmModal');
                const message = document.getElementById('confirmMessage');
                message.textContent = `Tem certeza de que deseja resetar a senha do porteiro ${porteiroNome}?`;
                modal.classList.remove('hidden');
            }

            // Fecha o modal sem realizar a ação
            document.getElementById('confirmCancel').addEventListener('click', function () {
                const modal = document.getElementById('confirmModal');
                modal.classList.add('hidden');
            });

            // Confirma a exclusão e envia o formulário
            document.getElementById('confirmOk').addEventListener('click', function () {
                if (resetPassword) {
                    resetPassword.submit(); // Submete o formulário de exclusão
                }
            });

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
