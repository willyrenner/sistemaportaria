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

                @if($errors->has('motivo'))
                    <div class="text-red-600 text-sm mt-2">
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
        <div class="bg-white shadow-lg p-6 rounded-lg">
            <h1 class="text-xl font-bold mb-4 text-center">MOVIMENTAÇÕES RECENTES</h1>
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-green-600 text-white">
                            <th class="px-4 py-2 border">Nome</th>
                            <th class="px-4 py-2 border">Tipo</th>
                            <th class="px-4 py-2 border">Permissão</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimentacoes as $movimentacao)
                            <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                <td class="px-4 py-2 border">{{ $movimentacao->aluno->nome }}</td>
                                <td class="px-4 py-2 border">
                                    {{ $movimentacao->tipo == 'entrada' ? 'Entrada' : 'Saída' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    {{ $movimentacao->permissao ? 'Autorizado' : 'Pendente' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cadastrar Entrada/Saída -->
        <div class="row-span-2 flex flex-col items-center bg-white shadow-lg p-6 rounded-lg">
            <h1 class="text-xl font-bold mb-4">CADASTRAR ENTRADA/SAÍDA DE VISITANTES</h1>
            <form action="{{ route('porteiro.registrarvisitante') }}" method="POST"
                class="flex flex-col gap-4 w-full max-w-md">
                @csrf
                <input type="text" name="nome" class="border border-gray-300 rounded px-4 py-2" placeholder="NOME"
                    required>
                <input type="number" name="cpf" class="border border-gray-300 rounded px-4 py-2" placeholder="CPF"
                    required>
                <input type="text" name="tipo" value="entrada" required hidden>
                <input type="text" name="motivo" class="border border-gray-300 rounded px-4 py-2" placeholder="MOTIVO">
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">ENVIAR</button>
            </form>

            @if(session('status_visitante'))
                <div class="mt-4 text-center text-xl font-semibold text-green-600">
                    {{ session('status_visitante') }}
                </div>
            @endif

            <h1 class="text-xl font-bold mb-4 mt-4">Confirmar Saída Visitante</h1>
            @forelse($registrosPendentes as $registro)
                <tr>
                    <td class="px-4 py-2 border"><b>Nome:</b> {{ $registro->nome }}</td>
                    <td class="px-4 py-2 border"><b>CPF:</b> {{ $registro->cpf }}</td>
                    <td class="px-4 py-2 border">{{ $registro->motivo ?? 'N/A' }}</td>
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
                    <td colspan="4" class="text-center px-4 py-2 border">
                        Nenhuma saída pendente para visitantes.
                    </td>
                </tr>
            @endforelse
            @if(session('status_visitante_saida'))
                <div class="mt-4 text-center text-xl font-semibold text-green-600">
                    {{ session('status_visitante_saida') }}
                </div>
            @endif

        </div>
        <!-- Menu -->
        <div class="flex flex-col items-center bg-white shadow-lg p-6 rounded-lg">
            <h1 class="text-xl font-bold mb-4">MENU</h1>
            <a href="/porteiro/visitantes" class="text-green-600 hover:underline mb-4">HISTÓRICO DE VISITANTES</a>
            <a href="/porteiro/alunos" class="text-green-600 hover:underline mb-4">HISTÓRICO DE ALUNOS</a>
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
</body>

<script>
    const confirmModal = document.getElementById('confirmModal');
    const confirmMessage = document.getElementById('confirmMessage');
    const confirmOk = document.getElementById('confirmOk');
    const confirmCancel = document.getElementById('confirmCancel');

    const form = document.querySelector('form[action="{{ route('registros.registrar') }}"]');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const matricula = form.querySelector('input[name="matricula"]').value;
        const tipo = form.querySelector('select[name="tipo"]').value;

        fetch(`/alunos/${matricula}`)
            .then(response => {
                if (!response.ok) throw new Error('Aluno não encontrado');
                return response.json();
            })
            .then(data => {
                confirmMessage.textContent = `Confirmar ${tipo === 'entrada' ? 'entrada' : 'saída'} de ${data.nome} (${data.matricula})?`;

                confirmModal.classList.remove('hidden');

                confirmOk.onclick = () => {
                    confirmModal.classList.add('hidden');
                    form.submit();
                };

                confirmCancel.onclick = () => {
                    confirmModal.classList.add('hidden');
                };
            })
            .catch(error => {
                alert('Erro: ' + error.message);
            });
    });
</script>

<script>
    const visitorForm = document.querySelector('form[action="{{ route('porteiro.registrarvisitante') }}"]');
    const visitorConfirmModal = document.getElementById('confirmModal');
    const visitorConfirmMessage = document.getElementById('confirmMessage');
    const visitorConfirmOk = document.getElementById('confirmOk');
    const visitorConfirmCancel = document.getElementById('confirmCancel');

    visitorForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const nome = visitorForm.querySelector('input[name="nome"]').value;
        const tipo = visitorForm.querySelector('input[name="tipo"]').value;

        if (!nome) {
            alert('Por favor, insira o nome do visitante.');
            return;
        }

        visitorConfirmMessage.textContent = `Confirmar ${tipo === 'entrada' ? 'entrada' : 'saída'} do visitante ${nome}?`;

        // Exibe o modal de confirmação
        visitorConfirmModal.classList.remove('hidden');

        // Confirmação
        visitorConfirmOk.onclick = () => {
            visitorConfirmModal.classList.add('hidden');
            visitorForm.submit();
        };

        // Cancelamento
        visitorConfirmCancel.onclick = () => {
            visitorConfirmModal.classList.add('hidden');
        };
    });

    // Capturar botões "Autorizar Saída"
    document.querySelectorAll('.autorizar-saida').forEach(button => {
        button.addEventListener('click', function () {
            const nome = button.getAttribute('data-nome');
            const registroId = button.getAttribute('data-registro-id');

            // Configura o modal
            confirmMessage.textContent = `Confirmar saída do visitante ${nome}?`;
            confirmModal.classList.remove('hidden');

            // Confirmar ação
            confirmOk.onclick = () => {
                confirmModal.classList.add('hidden');

                // Cria um formulário temporário para enviar a requisição
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/porteiro/confirmar-saida-visitante/${registroId}`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            };

            // Cancelar ação
            confirmCancel.onclick = () => {
                confirmModal.classList.add('hidden');
            };
        });
    });

</script>

<script>
    @if(Auth::guard('porteiro')->user()->password_reset_required)
        document.getElementById('updatePasswordModal').classList.remove('hidden');
    @endif
</script>

</html>