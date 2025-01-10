<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start ">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Autorizar Saídas de Menores') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded hover:bg-blue-600 inline-block">
                Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->has('motivo'))
                    <div class="text-red-600 text-sm mt-2">
                        {{ $errors->first('motivo') }}
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
                            <input type="text" name="motivo" placeholder="Motivo"
                                class="w-full px-3 py-2 rounded text-black" required>
                            <input type="hidden" name="tipo" value="saida">

                            <!-- Botão de Enviar -->
                            <button type="submit" class="w-full bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>

                <h2 class="text-2xl font-semibold mb-4">Saídas Pendentes de Menores</h2>
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left border">Matrícula</th>
                            <th class="px-4 py-2 text-left border">Nome</th>
                            <th class="px-4 py-2 text-left border">Data de Solicitação</th>
                            <th class="px-4 py-2 text-left border">Motivo</th>
                            <th class="px-4 py-2 text-left border">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrosPendentes as $registro)
                            <tr>
                                <td class="px-4 py-2 border">{{ $registro->aluno->matricula }}</td>
                                <td class="px-4 py-2 border">{{ $registro->aluno->nome }}</td>
                                <td class="px-4 py-2 border">{{ $registro->solicitacao }}</td>
                                <td class="px-4 py-2 border">{{ $registro->motivo ?? 'N/A' }}</td>
                                <td class="px-4 py-2 border">
                                    <form action="{{ route('registros.confirmar-saida', $registro->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                            Autorizar Saída
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center px-4 py-2 border">Nenhuma saída pendente para menores.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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