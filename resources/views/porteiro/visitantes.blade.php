<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" type="imagex/png" href="imagens/ifrn-logo.ico">
    <title>Painel Porteiro</title>
    <link rel="icon" href="{{ asset('img/ifrn-logo.ico') }}" type="image/x-icon">
</head>

<body class="bg-gray-100 text-gray-800">
    <header class="bg-green-600 text-white p-4 flex justify-between items-center">

        <a href="/porteiro-dashboard" class="flex items-center gap-12">
            <img src="img/ifrn-logo.png" alt="Logo IFRN" class="w-12" width="">
            <h1 class="text-2xl font-semibold">Sistema de Portaria - IFRN Caicó</h1>
        </a>
        <div class="flex items-center gap-4">
            <p class="text-lg">
                Porteiro:
                <span class="font-semibold">{{ $porteiro->name }}</span>
            </p>
            <form action="{{ route('porteiro.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 px-4 py-2 rounded text-white hover:bg-red-600">
                    Sair
                </button>
            </form>
        </div>
    </header>

    <div class="flex flex-col w-full justify-start px-6 py-4">
    <h1 class="font-semibold items-center text-xl leading-tight mb-4">Histórico de Visitantes</h1>
        <a href="/porteiro-dashboard" class="bg-green-500 w-56 text-center text-white px-4 py-2 rounded hover:bg-green-600">
            Voltar para o Dashboard
        </a>
    </div>

    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white text-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Listagem de Registros de Entrada -->
                <div id="lista-entradas" class="bg-white p-4 rounded shadow-lg mb-6">
                    <h2 class="text-2xl font-bold text-black mb-4 text-center">REGISTROS DE ENTRADA</h2>
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
                                        <td class="px-4 py-2 border text-gray-800">
                                            {{ $registro->tipo ? 'Entrada' : 'Pendente' }}
                                        </td>
                                        <td class="px-4 py-2 border text-gray-800">
                                            {{$registro->created_at ? date('d/m/Y - H:i', strtotime($registro->created_at)) : 'Pendente'}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Listagem de Registros de Saída -->
                <div id="lista-saidas" class="bg-white p-4 rounded shadow-lg">
                    <h2 class="text-2xl font-bold text-black mb-4 text-center">REGISTROS DE SAÍDA</h2>
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
                                                Saída
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border text-gray-800">
                                            @if(is_null($registro->saida))
                                                <span class="text-red-600">Saída não registrada</span>
                                            @else
                                                {{$registro->saida ? date('d/m/Y - H:i', strtotime($registro->saida)) : 'Pendente'}}
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