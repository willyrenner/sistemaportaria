<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Autorizar Saídas de Menores') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="bg-green-500 text-white px-4 py-2 mt-4 rounded hover:bg-green-600 inline-block">
                Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-4 rounded-lg shadow-lg overflow-hidden sm:rounded-lg">
                @if(session('status'))
                    <div class="bg-yellow-500 text-white p-4 rounded mb-4">
                        {{ session('status') }}
                    </div>
                @endif

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
                <button onclick="toggleForm()"
                    class="w-full bg-green-500 text-white px-4 py-2 rounded mb-6 hover:bg-green-600">
                    Novo Registro
                </button>

                <!-- Formulário de Registro de Saída -->
                <div id="cadastro-saida" class="bg-white p-4 rounded shadow-lg mb-6 hidden">
                    <h2 class="text-2xl font-semibold mb-4">Registrar Saída</h2>

                    <form action="{{ route('registros.store') }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <input type="text" name="matricula" placeholder="Matrícula do Aluno"
                                class="w-full px-3 py-2 rounded text-black" required>
                            <input type="text" name="motivo" placeholder="Motivo"
                                class="w-full px-3 py-2 rounded text-black" required>
                            <input type="hidden" name="tipo" value="saida">

                            <button type="submit"
                                class="w-full text-white bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>

                <h2 class="text-2xl font-semibold mb-4">Saídas Pendentes de Menores</h2>
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-green-600 text-white">
                            <th class="px-4 py-2 text-left border">Matrícula</th>
                            <th class="px-4 py-2 text-left border">Nome</th>
                            <th class="px-4 py-2 text-left border">Responsável</th>
                            <th class="px-4 py-2 text-left border">Telefone</th>
                            <th class="px-4 py-2 text-left border">Data de Solicitação</th>
                            <th class="px-4 py-2 text-left border">Motivo</th>
                            <th class="px-4 py-2 text-left border">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrosPendentes as $registro)
                            <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                <td class="px-4 py-2 border">{{ $registro->aluno->matricula }}</td>
                                <td class="px-4 py-2 border">{{ $registro->aluno->nome }}</td>
                                <td class="px-4 py-2 border">{{ $registro->aluno->responsavel->nome }}</td>
                                <td class="px-4 py-2 border">{{ $registro->aluno->responsavel->telefone }}</td>
                                <td class="px-4 py-2 border">
                                    {{$registro->solicitacao ? date('d/m/Y - H:i', strtotime($registro->solicitacao)) : 'Pendente'}}
                                </td>
                                <td class="px-4 py-2 border">{{ $registro->motivo ?? 'N/A' }}</td>
                                <td class="px-4 py-2 border flex justify-around">
                                    <form action="{{ route('registros.confirmar-saida', $registro->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit"
                                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                            Autorizar
                                        </button>
                                    </form>

                                    <form action="{{ route('registros.negar-saida', $registro->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit"
                                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                            Negar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center px-4 py-2 border">
                                    Nenhuma saída pendente para menores.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/autorizar_menores.js') }}"></script>
</x-app-layout>