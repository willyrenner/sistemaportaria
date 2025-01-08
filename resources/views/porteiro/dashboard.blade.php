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

    <div class="grid grid-cols-3 grid-rows-auto gap-6 p-6">
        <!-- Solicitar Aluno -->
        <div class="flex flex-col items-center bg-white shadow-lg p-6 rounded-lg">
            <h1 class="text-xl font-bold mb-4">SOLICITAR ENTRADA/SAÍDA ALUNO</h1>
            <form action="{{ route('registros.registrar') }}" method="POST" class="flex flex-col gap-4 w-full max-w-md">
                @csrf
                <input type="text" name="matricula" class="border border-gray-300 rounded px-4 py-2"
                    placeholder="MATRICULA" required>
                <select name="tipo" class="border border-gray-300 rounded px-4 py-2" required>
                    <option value="entrada">ENTRADA</option>
                    <option value="saida">SAÍDA</option>
                </select>
                <input type="text" name="motivo" class="border border-gray-300 rounded px-4 py-2" placeholder="MOTIVO">
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">ENVIAR</button>
            </form>

            <!-- Exibir a mensagem após o envio -->
            @if(session('status'))
                <div class="mt-4 text-center text-xl font-semibold text-green-600">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <!-- Movimentações Recentes -->
        <div class="flex flex-col items-center bg-white shadow-lg p-6 rounded-lg">
            <h1 class="text-xl font-bold mb-4">MOVIMENTAÇÕES<br>RECENTES</h1>
            @foreach($movimentacoes as $movimentacao)
                <div class="text-gray-700 mb-4">
                    <p class="font-semibold">{{ $movimentacao->aluno->nome }}</p>
                    <p class="text-gray-600">{{ $movimentacao->tipo == 'entrada' ? 'Entrada' : 'Saída' }}</p>
                    <p class="text-gray-600">
                        {{ $movimentacao->permissao ? 'Autorizado' : 'Pendente' }}
                    </p>
                </div>
            @endforeach
        </div>
        <!-- Cadastrar Entrada/Saída -->
        <div class="row-span-2 flex flex-col items-center bg-white shadow-lg p-6 rounded-lg">
            <h1 class="text-xl font-bold mb-4">CADASTRAR ENTRADA/SAÍDA VISITANTES</h1>
            <form class="flex flex-col gap-4 w-full max-w-md">
                <input type="text" class="border border-gray-300 rounded px-4 py-2" placeholder="NOME">
                <input type="number" class="border border-gray-300 rounded px-4 py-2" placeholder="CPF">
                <input type="datetime-local" class="border border-gray-300 rounded px-4 py-2">
                <select class="border border-gray-300 rounded px-4 py-2">
                    <option value="entrada">ENTRADA</option>
                    <option value="saida">SAÍDA</option>
                </select>
                <input type="text" class="border border-gray-300 rounded px-4 py-2" placeholder="MOTIVO">
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">ENVIAR</button>
            </form>
        </div>
        <!-- Menu -->
        <div class="flex flex-col items-center bg-white shadow-lg p-6 rounded-lg">
            <h1 class="text-xl font-bold mb-4">MENU</h1>
            <a href="#" class="text-green-600 hover:underline mb-2">SOLICITAR SAÍDA DE ALUNO</a>
            <a href="#" class="text-green-600 hover:underline mb-2">CADASTRAR ENTRADA/SAÍDA DE MAIORES</a>
            <a href="#" class="text-green-600 hover:underline mb-4">HISTÓRICO DE MOVIMENTAÇÕES</a>
            <div class="flex gap-2 w-full max-w-md">
                <input type="text" class="border border-gray-300 rounded flex-1 px-4 py-2" placeholder="BUSCAR ALUNO">
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">BUSCAR</button>
            </div>
        </div>
        <!-- Observações -->
        <div class="flex flex-col items-center bg-white shadow-lg p-6 rounded-lg">
            <h1 class="text-xl font-bold mb-4">OBSERVAÇÕES</h1>
            <textarea class="border border-gray-300 rounded px-4 py-2 w-full h-32 mb-4"></textarea>
            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">ENVIAR</button>
        </div>
    </div>
</body>

</html>