<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registro de Saídas e Entradas') }}
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

                <!-- Botão para novo registro -->
                <button onclick="toggleForm()" class="w-full bg-blue-500 px-4 py-2 rounded mb-6 hover:bg-blue-600">Novo
                    Registro</button>

                <!-- Formulário de Registro de Saída -->
                <div id="cadastro-saida" class="bg-gray-800 p-4 rounded shadow-lg mb-6 hidden">
                    <h2 class="text-2xl font-semibold mb-4">Registrar Saída</h2>

                    <form action="{{ route('registros.store') }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <!-- Campo Matrícula -->
                            <input type="text" name="matricula" placeholder="Matrícula do Aluno"
                                class="w-full px-3 py-2 rounded text-black" required>
                            <input type="hidden" name="tipo" value="saida">

                            <!-- Botão de Enviar -->
                            <button type="submit" class="w-full bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Listagem de Registros de Entrada -->
                <div id="lista-entradas" class="bg-gray-800 p-4 rounded shadow-lg mb-6">
                    <h2 class="text-2xl font-semibold mb-4">Registros de Entrada</h2>
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left border">Matrícula</th>
                                <th class="px-4 py-2 text-left border">Aluno</th>
                                <th class="px-4 py-2 text-left border">Tipo</th>
                                <th class="px-4 py-2 text-left border">Data e Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registros->where('tipo', 'entrada') as $registro)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $registro->aluno->matricula }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->aluno->nome }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->tipo }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->solicitacao }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Listagem de Registros de Saída -->
                <div id="lista-saidas" class="bg-gray-800 p-4 rounded shadow-lg">
                    <h2 class="text-2xl font-semibold mb-4">Registros de Saída</h2>
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left border">Matrícula</th>
                                <th class="px-4 py-2 text-left border">Aluno</th>
                                <th class="px-4 py-2 text-left border">Tipo</th>
                                <th class="px-4 py-2 text-left border">Data Solicitação</th>
                                <th class="px-4 py-2 text-left border">Data Saída</th>
                                <th class="px-4 py-2 text-left border">Motivo</th>
                                <th class="px-4 py-2 text-left border">Permissão</th> <!-- Nova coluna -->
                                <th class="px-4 py-2 text-left border">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registros->where('tipo', 'saida') as $registro)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $registro->aluno->matricula }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->aluno->nome }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->tipo }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->solicitacao }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->saida ?? 'Pendente' }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->motivo ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 border">
                                        {{ $registro->permissao ?? 'Aguardando autorização' }}
                                    </td>
                                    <td class="px-4 py-2 border flex space-x-2 justify-start">
                                        @if(!$registro->saida)
                                            <!-- Botão para confirmar saída -->
                                            <button onclick="toggleConfirmForm({{ $registro->id }})"
                                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                Confirmar Saída
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Formulário para Confirmar Saída -->
                                <tr id="confirm-form-{{ $registro->id }}" style="display: none;">
                                    <td colspan="7" class="px-4 py-2 border">
                                        <form action="{{ route('registros.confirmar-saida', $registro->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <p>O horário de saída será registrado automaticamente como o horário atual.</p>
                                            <div class="space-y-3">
                                                <button type="submit"
                                                    class="w-full bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                                    Confirmar
                                                </button>
                                                <button type="button" onclick="toggleConfirmForm({{ $registro->id }})"
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
            const form = document.getElementById('cadastro-saida');
            form.classList.toggle('hidden');
        }

        function toggleConfirmForm(id) {
            const form = document.getElementById('confirm-form-' + id);
            form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
</x-app-layout>