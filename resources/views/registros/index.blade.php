<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start ">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Registro de Saídas e Entradas') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded hover:bg-blue-600 inline-block">
                Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-gray-900 text-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                

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
                                <th class="px-4 py-2 text-left border">Permissão</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registros->where('tipo', 'entrada') as $registro)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $registro->aluno->matricula }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->aluno->nome }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->tipo }}</td>
                                    <td class="px-4 py-2 border">{{ $registro->solicitacao }}</td>
                                    <td class="px-4 py-2 border">
                                        {{ $registro->permissao ?? 'Aguardando autorização' }}
                                    </td>
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
                                <th class="px-4 py-2 text-left border">Permissão</th>
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
</x-app-layout>