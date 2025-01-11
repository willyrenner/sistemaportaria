<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" type="imagex/png" href="imagens/ifrn-logo.ico">
    <title>PAINEL PORTEIRO</title>
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-green-600 text-white p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Sistema de Portaria - IFRN Caicó</h1>
        <div class="flex items-center gap-4">
            <p class="text-lg">
                Porteiro:
                <span class="font-semibold">{{ $porteiro->name }}</span>
            </p>
            <form action="{{ route('porteiro.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 px-4 py-2 rounded text-white hover:bg-red-500">
                    Logout
                </button>
            </form>
        </div>
    </header>
    <div class="py-12 bg-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Listagem de Registros de Entrada -->
                <div id="lista-entradas" class="bg-gray-800 p-4 rounded shadow-lg mb-6">
                    <h2 class="text-2xl font-semibold mb-4 text-center">Registros de Entrada</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border-collapse border border-green-400">
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
                                        <td class="px-4 py-2 border text-gray-800">{{ $registro->nome }}</td>
                                        <td class="px-4 py-2 border text-gray-800">{{ $registro->cpf }}</td>
                                        <td class="px-4 py-2 border text-gray-800">{{ $registro->tipo }}</td>
                                        <td class="px-4 py-2 border text-gray-800">{{ $registro->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Listagem de Registros de Saída -->
                <div id="lista-saidas" class="bg-gray-800 p-4 rounded shadow-lg">
                    <h2 class="text-2xl font-semibold mb-4 text-center">Registros de Saída</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border-collapse border border-green-400">
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
                                        <td class="px-4 py-2 border text-gray-800">{{ $registro->nome }}</td>
                                        <td class="px-4 py-2 border text-gray-800">{{ $registro->cpf }}</td>
                                        <td class="px-4 py-2 border text-gray-800">
                                            @if(is_null($registro->saida))
                                                <span class="text-red-600">Pendente</span>
                                            @elseif ($registro->saida)
                                                <span>Saída</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border text-gray-800">
                                            @if(is_null($registro->saida))
                                                <span class="text-red-600">Saída não registrada</span>
                                            @else
                                                {{ $registro->saida }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>