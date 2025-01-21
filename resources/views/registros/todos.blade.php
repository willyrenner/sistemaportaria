<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start ">
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
            <div class="bg-white text-white overflow-hidden shadow-lg sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4 text-black">Registros Unificados</h2>
                <table class="table-auto w-full border-collapse border border-gray-200 text-black">
                    <thead>
                        <tr class="bg-green-600 text-white">
                            <th class="px-4 py-2 text-left border">Categoria</th>
                            <th class="px-4 py-2 text-left border">Nome</th>
                            <th class="px-4 py-2 text-left border">Tipo</th>
                            <th class="px-4 py-2 text-left border">Motivo</th>
                            <th class="px-4 py-2 text-left border">Data e Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registros as $registro)
                            <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                <td class="px-4 py-2 border">{{ ucfirst($registro->categoria) }}</td>
                                <td class="px-4 py-2 border">
                                    {{ $registro->categoria == 'aluno' ? $registro->aluno->nome . ' (' . $registro->aluno->matricula . ')' : $registro->nome . ' (CPF ' . $registro->cpf . ')' }}
                                </td>
                                
                                <td class="px-4 py-2 border">
                                    {{ $registro->categoria == 'aluno' ? $registro->tipo : $registro->tipo }}
                                </td>
                                <td class="px-4 py-2 border">
                                    {{ $registro->motivo ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    {{ date('d/m/Y - H:i', strtotime($registro->data)) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
