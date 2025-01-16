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

    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-4 rounded-lg shadow-lg overflow-hidden sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('status'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('status') }}
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
                            <!-- Campo Matrícula -->
                            <input type="text" name="matricula" placeholder="Matrícula do Aluno"
                                class="w-full px-3 py-2 rounded text-black" required>
                            <input type="text" name="motivo" placeholder="Motivo"
                                class="w-full px-3 py-2 rounded text-black" required>
                            <input type="hidden" name="tipo" value="saida">

                            <!-- Botão de Enviar -->
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

                                        <button type="button"
                                            class="autorizar-saida px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                                            data-nome="{{ $registro->aluno->nome }} ({{ $registro->aluno->matricula }})"
                                            data-registro-id="{{ $registro->id }}">
                                            Autorizar Saída
                                        </button>
                                    </form>

                                    <form action="{{ route('registros.negar-saida', $registro->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <button type="button"
                                            class="negar-saida px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                                            data-nome="{{ $registro->aluno->nome }} ({{ $registro->aluno->matricula }})"
                                            data-registro-id="{{ $registro->id }}">
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

    <!-- Modal -->
    <div id="confirmModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden backdrop-filter backdrop-blur-sm">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-center" id="confirmMessage">Confirmando ação...</h2>
            <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4 hidden"></div>
            <div class="flex flex-col gap-4 justify-between">
                <!-- Campo Observação -->
                <input type="text" name="observacao_responsavel" id="observacao_responsavel" placeholder="Observação"
                    class="hidden w-full px-3 py-2 border rounded text-black" required />

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
    </div>

    <script>
        document.querySelectorAll('.autorizar-saida').forEach(button => {
            button.addEventListener('click', function () {
                const nome = button.getAttribute('data-nome');
                const registroId = button.getAttribute('data-registro-id');
                const observacaoInput = document.getElementById('observacao_responsavel');

                // Configura o modal
                confirmMessage.textContent = `Confirmar saída do aluno ${nome}?`;
                confirmModal.classList.remove('hidden');

                // Exibe o campo de observação
                observacaoInput.classList.remove('hidden');

                // Confirmar ação
                confirmOk.onclick = () => {

                    if (!observacaoInput.value.trim()) {
                        const errorDiv = document.getElementById('error-message');
                        errorDiv.textContent = "O campo observação é obrigatório";
                        errorDiv.classList.remove('hidden');
                        return;
                    }

                    confirmModal.classList.add('hidden');

                    // Cria um formulário temporário para enviar a requisição
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/registros/${registroId}/confirmar-saida`;

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

                    // Inclui o valor da observação no formulário
                    const observacao = document.createElement('input');
                    observacao.type = 'hidden';
                    observacao.name = 'observacao_responsavel';
                    observacao.value = observacaoInput.value;
                    form.appendChild(observacao);

                    document.body.appendChild(form);
                    form.submit();
                };

                // Cancelar ação
                confirmCancel.onclick = () => {
                    confirmModal.classList.add('hidden');
                    observacaoInput.value = ''; // Limpa o campo de observação
                    observacaoInput.classList.add('hidden'); // Esconde o campo novamente
                };
            });
        });

        document.querySelectorAll('.negar-saida').forEach(button => {
            button.addEventListener('click', function () {
                const nome = button.getAttribute('data-nome');
                const registroId = button.getAttribute('data-registro-id');
                const observacaoInput = document.getElementById('observacao_responsavel');

                // Configura o modal
                confirmMessage.textContent = `Negar saída do aluno ${nome}?`;
                confirmModal.classList.remove('hidden');

                // Exibe o campo de observação
                observacaoInput.classList.remove('hidden');

                // Confirmar ação
                confirmOk.onclick = () => {

                    if (!observacaoInput.value.trim()) {
                        const errorDiv = document.getElementById('error-message');
                        errorDiv.textContent = "O campo observação é obrigatório";
                        errorDiv.classList.remove('hidden');
                        return;
                    }

                    confirmModal.classList.add('hidden');

                    // Cria um formulário temporário para enviar a requisição
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/registros/${registroId}/negar-saida`;

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

                    // Inclui o valor da observação no formulário
                    const observacao = document.createElement('input');
                    observacao.type = 'hidden';
                    observacao.name = 'observacao_responsavel';
                    observacao.value = observacaoInput.value;
                    form.appendChild(observacao);

                    document.body.appendChild(form);
                    form.submit();
                };

                // Cancelar ação
                confirmCancel.onclick = () => {
                    confirmModal.classList.add('hidden');
                    observacaoInput.value = ''; // Limpa o campo de observação
                    observacaoInput.classList.add('hidden'); // Esconde o campo novamente
                };
            });
        });

        const confirmModal = document.getElementById('confirmModal');
        const confirmMessage = document.getElementById('confirmMessage');
        const confirmOk = document.getElementById('confirmOk');
        const confirmCancel = document.getElementById('confirmCancel');

        const form = document.querySelector('form[action="{{ route('registros.store') }}"]');

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const matricula = form.querySelector('input[name="matricula"]').value;
            const tipo = form.querySelector('input[name="tipo"]').value;

            fetch(`/alunos/${matricula}`)
                .then(response => {
                    if (!response.ok) throw new Error('Aluno não encontrado');
                    return response.json();
                })
                .then(data => {
                    confirmMessage.textContent = `Confirmar ${tipo === 'entrada' ? 'entrada' : 'saída'} de ${data.nome}
    (${data.matricula})?`;

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

        function toggleForm() {
            const form = document.getElementById('cadastro-saida');
            form.classList.toggle('hidden');
        }
    </script>
</x-app-layout>