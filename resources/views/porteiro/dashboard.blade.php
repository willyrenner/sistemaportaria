<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Painel Porteiro</title>
    <link rel="icon" href="{{ asset('img/ifrn-logo.ico') }}" type="image/x-icon">
</head>

<body class="bg-gray-100 text-gray-800">
    <header class="bg-green-600 text-white p-4 flex flex-col md:flex-row justify-between items-center gap-4">
        <a href="/porteiro-dashboard"
            class="flex flex-col md:flex-row items-center gap-4 md:gap-12 text-center md:text-left">
            <img src="img/ifrn-logo.png" alt="Logo IFRN" class="w-12">
            <h1 class="text-xl md:text-2xl font-semibold">
                Sistema de Portaria - IFRN Caicó
            </h1>
        </a>
        <div class="flex flex-col md:flex-row items-center gap-4 text-center md:text-right">
            <p class="text-lg">
                Porteiro:
                <span class="font-semibold">{{ $porteiro->name }}</span>
            </p>
            <form action="{{ route('porteiro.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 px-4 py-2 rounded text-white hover:bg-red-500">
                    Sair
                </button>
            </form>
        </div>
    </header>

    <div class="w-full mx-auto flex justify-center shadow text-black py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="font-semibold text-xl leading-tight text-center">Painel Porteiro</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4 md:p-6">
        <!-- Solicitar Aluno -->
        <div class="flex flex-col items-center bg-white shadow-lg p-6 rounded-lg col-span-1">
            <div id="error-message-aluno" class="bg-red-500 text-white p-4 rounded mb-4 hidden"></div>
            <h1 class="text-xl font-bold mb-4">SOLICITAR ENTRADA/SAÍDA ALUNO</h1>
            <!-- TODO: Ver como passar os dados desse form para o js sem usar nada do laravel -->
            <!-- <form id="aluno-form" action="{{ route('registros.registrar') }}"> -->
            <form id="aluno-form" action="{{ route('registros.registrar') }}" method="POST"
                class="flex flex-col gap-4 w-full max-w-md">
                @csrf
                <input type="number" name="matricula" class="border border-gray-300 rounded px-4 py-2"
                    placeholder="MATRICULA" oninput="limitInputLength(this, 14)" required>
                <select name="tipo" class="border border-gray-300 rounded px-4 py-2" required>
                    <option value="entrada">ENTRADA</option>
                    <option value="saida">SAÍDA</option>
                </select>
                <input type="text" name="motivo" class="border border-gray-300 rounded px-4 py-2" placeholder="MOTIVO">

                @if($errors->has('motivo'))
                    <div class="bg-red-500 text-white p-4 rounded mb-4">
                        {{ $errors->first('motivo') }}
                    </div>
                @endif

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
        <div class="row-span-2 flex flex-col bg-white shadow-lg p-6 rounded-lg col-span-1">
            <h1 class="text-xl font-bold mb-4 text-center">MOVIMENTAÇÕES RECENTES</h1>
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-green-600 text-white">
                            <th class="px-4 py-2 border">Nome</th>
                            <th class="px-4 py-2 border">Tipo</th>
                            <th class="px-4 py-2 border">Permissão</th>
                            <th class="px-4 py-2 border">Data/Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimentacoes as $movimentacao)
                            <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                <td class="px-4 py-2 border">{{ $movimentacao->aluno->nome }}</td>
                                <td class="px-4 py-2 border">
                                    {{ $movimentacao->tipo == 'entrada' ? 'Entrada' : 'Saída' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    {{ $movimentacao->permissao ? $movimentacao->permissao : 'Pendente' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    {{$movimentacao->saida ? date('d/m/Y - H:i', strtotime($movimentacao->saida)) : 'Pendente'}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-2 border">
                                    Nenhuma movimentação recente de alunos.
                                </td>
                                <td class="px-4 py-2 border">----</td>
                                <td class="px-4 py-2 border">----</td>
                                <td class="px-4 py-2 border">----</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cadastrar Entrada/Saída -->
        <div class="row-span-2 flex flex-col items-center bg-white shadow-lg p-6 rounded-lg">
            <h1 class="text-xl font-bold mb-4">CADASTRAR ENTRADA/SAÍDA DE VISITANTES</h1>
            <form id='visitor-form' action="{{ route('porteiro.registrarvisitante') }}" method="POST"
                class="flex flex-col gap-4 w-full overflow-x-auto">
                @csrf
                <input type="text" name="nome" class="border border-gray-300 rounded px-4 py-2" placeholder="NOME"
                    required>
                <input type="number" id="cpf" name="cpf" class="border border-gray-300 rounded px-4 py-2"
                    placeholder="CPF" oninput="limitInputLength(this, 11)" required>
                <input type="text" name="tipo" value="entrada" required hidden>
                <input type="text" name="motivo" class="border border-gray-300 rounded px-4 py-2" placeholder="MOTIVO"
                    minlength="5" required>
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">ENVIAR</button>
            </form>

            @if(session('status_visitante'))
                <div class="mt-4 text-center text-xl font-semibold text-green-600">
                    {{ session('status_visitante') }}
                </div>
            @endif

            @if($errors->has('motivoVisitante'))
                <div class="text-red-600 text-sm mt-2">
                    {{ $errors->first('motivoVisitante') }}
                </div>
            @endif

            @if($errors->has('cpf'))
                <div class="text-red-600 text-sm mt-2">
                    {{ $errors->first('cpf') }}
                </div>
            @endif

            <h1 class="text-xl font-bold mb-4 mt-4 text-center">Confirmar Saída Visitante</h1>
            <div class="overflow-x-auto w-full">
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-green-600 text-white">
                            <th class="px-4 py-2 border">Nome</th>
                            <th class="px-4 py-2 border">CPF</th>
                            <th class="px-4 py-2 border">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrosPendentes as $registro)
                            <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                <td class="px-4 py-2 border">{{ $registro->nome }}</td>
                                <td class="px-4 py-2 border">
                                    {{ $registro->cpf }}
                                </td>
                                <td class="px-4 py-2 border">
                                    <button type="button"
                                        class="autorizar-saida px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                                        data-nome="{{ $registro->nome }}" data-registro-id="{{ $registro->id }}">
                                        Confirmar Saída
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-2 border">
                                    Nenhuma saída pendente para visitantes.
                                </td>
                                <td class="px-4 py-2 border">----</td>
                                <td class="px-4 py-2 border">----</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(session('status_visitante_saida'))
                <div class="mt-4 text-center text-xl font-semibold text-green-600">
                    {{ session('status_visitante_saida') }}
                </div>
            @endif
        </div>

        <!-- Menu -->
        <div class="flex flex-col items-center bg-white shadow-lg p-6 rounded-lg">
            <h1 class="text-xl font-bold mb-4">MENU</h1>
            <a href="/porteiro-visitantes" class="text-green-600 hover:underline mb-4">HISTÓRICO DE VISITANTES</a>
            <a href="/porteiro-alunos" class="text-green-600 hover:underline mb-4">HISTÓRICO DE ALUNOS</a>
            <h1 class="text-xl font-bold mb-4">BUSCAR ALUNO</h1>
            <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4 hidden"></div>
            <div class="flex gap-2 w-full max-w-md">
                <input type="number" id="buscarMatricula" class="border border-gray-300 rounded flex-1 px-4 py-2"
                    placeholder="INFORME A MATRICULA" oninput="limitInputLength(this, 14)">
                <button id="buscarAluno"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">BUSCAR</button>
            </div>
        </div>
    </div>

    <div id="confirmModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden backdrop-filter backdrop-blur-sm">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-center" id="confirmMessage">Confirmando ação...</h2>
            <div class="flex justify-between">
                <button id="confirmOk" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
                    Ok
                </button>
                <button id="confirmCancel" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-500">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <div id="updatePasswordModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden backdrop-filter backdrop-blur-sm">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            @if($errors->has('password'))
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    {{ $errors->first('password') }}
                </div>
            @endif
            <h2 class="text-2xl font-bold mb-4 text-center">Atualize sua Senha</h2>
            <form id="passwordUpdateForm" action="{{ route('porteiro.password.update.submit') }}" method="POST">
                @csrf
                <div class="flex flex-col gap-4">
                    <div>
                        <label for="password" class="block font-semibold">Nova Senha</label>
                        <input type="password" name="password" id="password"
                            class="border border-gray-300 rounded px-4 py-2 w-full" required>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block font-semibold">Confirme a Nova Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="border border-gray-300 rounded px-4 py-2 w-full" required>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">Atualizar
                        Senha</button>
                </div>
            </form>
        </div>
    </div>

    <div id="alunoModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden backdrop-filter backdrop-blur-sm">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-center">Informações do Aluno</h2>
            <div id="alunoInfo">
                <p><strong>Nome:</strong> <span id="alunoNome"></span></p>
                <p><strong>Matrícula:</strong> <span id="alunoMatricula"></span></p>
                <p><strong>Idade:</strong> <span id="alunoIdade"></span></p>
                <p><strong>Responsável:</strong> <span id="alunoResponsavel"></span></p>
                <p><strong>Curso:</strong> <span id="alunoCurso"></span></p>
            </div>
            <div class="flex justify-end mt-4">
                <button id="alunoClose" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-500">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</body>

<script src="{{ asset('js/porteiro_dashboard.js') }}"></script>

<script>
    @if(Auth::guard('porteiro')->user()->password_reset_required)
        document.getElementById('updatePasswordModal').classList.remove('hidden');
    @endif
</script>

</html>
