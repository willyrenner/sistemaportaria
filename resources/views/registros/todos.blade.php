<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Todos os Registros') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="bg-green-500 text-white px-4 py-2 mt-4 rounded hover:bg-green-600 inline-block">
                Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4 text-black">Registros Unificados</h2>

                <!-- Filtro por nome -->
                <div class="mb-4">
                    <form method="GET" action="{{ route('registros.todos') }}" class="flex items-center gap-2">
                        <input type="text" name="nome" class="w-full px-3 py-2 rounded text-black border"
                            value="{{ request('nome') }}" oninput="handleInputChange(this)" placeholder="Digite o nome...">
                        
                        <button type="submit" class="uppercase bg-blue-500 px-4 py-2 rounded text-white hover:bg-blue-600">
                            Filtrar
                        </button>
                    </form>
                </div>

                <!-- Tabela -->
                @if($registros->isEmpty())
                    <p class="text-gray-500 text-center">Nenhum registro encontrado.</p>
                @else
                    <table class="table-auto w-full border-collapse border border-gray-200 text-black text-sm">
                        <thead>
                            <tr class="bg-green-600 text-white text-center">
                                <th class="px-4 py-2 border">Categoria</th>
                                <th class="px-4 py-2 border">Nome</th>
                                <th class="px-4 py-2 border">Tipo</th>
                                <th class="px-4 py-2 border">Motivo</th>
                                <th class="px-4 py-2 border">Data e Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registros as $registro)
                                <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                    <td class="px-4 py-2 border text-center">{{ ucfirst($registro->categoria) }}</td>
                                    <td class="px-4 py-2 border">
                                        @if($registro->categoria == 'aluno')
                                            {{ $registro->aluno->nome }} ({{ $registro->aluno->matricula }})
                                        @else
                                            {{ $registro->nome }} (CPF {{ $registro->cpf }})
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border text-center">{{ $registro->tipo }}</td>
                                    <td class="px-4 py-2 border text-center">
                                        {{ $registro->motivo ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 border text-center">
                                        {{ date('d/m/Y - H:i', strtotime($registro->data)) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <script src="{{ asset('js/registros-todos.js') }}"></script>
</x-app-layout>