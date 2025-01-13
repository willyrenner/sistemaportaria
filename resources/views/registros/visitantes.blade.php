<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start ">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Registro de Saídas e Entradas de Visitantes') }}
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


                <!-- Listagem de Registros de Entrada -->
                <div id="lista-entradas" class="text-black p-4 rounded shadow-lg mb-6">
                    <h2 class="text-2xl font-semibold mb-4">Registros de Entrada</h2>
                    <table class="table-auto w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-green-600 text-white">
                                <th class="px-4 py-2 text-left border">Nome</th>
                                <th class="px-4 py-2 text-left border">CPF</th>
                                <th class="px-4 py-2 text-left border">Tipo</th>
                                <th class="px-4 py-2 text-left border">Data e Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registros->where('tipo', 'entrada') as $registro)
                                <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                    <td class="px-4 py-2 border text-black">{{ $registro->nome }}</td>
                                    <td class="px-4 py-2 border text-black">{{ $registro->cpf }}</td>
                                    <td class="px-4 py-2 border text-black">{{ $registro->tipo }}</td>
                                    <td class="px-4 py-2 border text-black">{{ $registro->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Listagem de Registros de Saída -->
                <div id="lista-saidas" class="text-black p-4 rounded shadow-lg">
                    <h2 class="text-2xl font-semibold mb-4">Registros de Saída</h2>
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-green-600 text-white">
                                <th class="px-4 py-2 text-left border">Nome</th>
                                <th class="px-4 py-2 text-left border">CPF</th>
                                <th class="px-4 py-2 text-left border">Tipo</th>
                                <th class="px-4 py-2 text-left border">Data e Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registros as $registro)
                                <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                    <td class="px-4 py-2 border text-black">{{ $registro->nome }}</td>
                                    <td class="px-4 py-2 border text-black">{{ $registro->cpf }}</td>
                                    <td class="px-4 py-2 border text-black">
                                        @if(is_null($registro->saida))
                                            <span class="text-red-600">Pendente</span>
                                        @elseif ($registro->saida)
                                            <span>Saída</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border text-black">
                                        @if(is_null($registro->saida))
                                            <span class="text-red-600">Saída não registrada</span>
                                        @else
                                            {{ $registro->saida }}
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
</x-app-layout>
