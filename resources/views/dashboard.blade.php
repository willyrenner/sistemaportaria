<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-center">
            {{ __('Painel COAPAC') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-100 text-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6">
                <div id="dashboard" class="grid grid-cols-1 gap-6 md:grid-cols-3">

                    <!-- Menu -->
                    <div id="menu" class="bg-white p-4 rounded-lg shadow-lg">
                        <h2 class="text-xl font-semibold mb-4 text-black-700">MENU</h2>
                        <ul class="space-y-2">
                            <li><a href="/autorizar-menores" class="text-lg block hover:text-green-500">AUTORIZAR SAÍDA DE MENORES</a></li>
                            <li><a href="/registros" class="text-lg block hover:text-green-500">HISTÓRICO DE SAÍDAS/ENTRADAS</a></li>
                            <li><a href="/registros-visitantes" class="text-lg block hover:text-green-500">HISTÓRICO DE VISITANTES</a></li>
                            <li><a href="/porteiros" class="text-lg block hover:text-green-500">MENU PORTEIROS</a></li>
                            <li><a href="/cursos" class="text-lg block hover:text-green-500">MENU CURSOS</a></li>
                            <li><a href="/responsaveis" class="text-lg block hover:text-green-500">MENU RESPONSÁVEIS</a></li>
                            <li><a href="/alunos" class="text-lg block hover:text-green-500">MENU ALUNO</a></li>
                        </ul>
                        <div class="flex flex-col mt-8">
                            <h1 class="text-lg font-semibold mb-2 text-black-700">BUSCAR ALUNO</h1>
                            <input type="text" id="buscarMatricula" class="border border-gray-300 rounded w-full px-4 py-2 mb-2" placeholder="INFORME A MATRICULA">
                            <button id="buscarAluno" class="bg-green-500 w-full text-white px-4 py-2 rounded hover:bg-green-600">BUSCAR</button>
                        </div>
                    </div>

                    <!-- Cadastro de Porteiros -->
                    <div id="cadastro-porteiros" class="bg-white p-4 rounded-lg shadow-lg">
                        <h2 class="text-xl font-semibold mb-4 text-black-700">CADASTRO DE PORTEIROS</h2>

                        <!-- Exibição de Mensagens -->
                        @if(session('success'))
                            <div class="bg-green-500 text-white p-4 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="bg-red-500 text-white p-4 rounded mb-4">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('porteiros.store') }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <!-- Campo Nome -->
                                <input type="text" name="name" placeholder="NOME" class="w-full px-3 py-2 rounded text-black border border-gray-300" required>

                                <input type="hidden" name="email" value="">

                                <!-- Campo CPF -->
                                <input type="text" name="cpf" placeholder="CPF" class="w-full px-3 py-2 rounded text-black border border-gray-300" required>

                                <!-- Campo Matrícula -->
                                <input type="text" name="matricula" placeholder="MATRÍCULA" class="w-full px-3 py-2 rounded text-black border border-gray-300" required>

                                <!-- Campos Fixos -->
                                <input type="hidden" name="role" value="porteiro">

                                <!-- Campo Turnos -->
                                <div>
                                    <h4 class="font-semibold text-lg mb-2 text-black-700">TURNO</h4>
                                    <select name="turno" class="w-full px-3 py-2 rounded text-black border border-gray-300">
                                        <option value="">Selecione o turno</option>
                                        <option value="matutino">MATUTINO</option>
                                        <option value="vespertino">VESPERTINO</option>
                                        <option value="noturno">NOTURNO</option>
                                    </select>
                                </div>

                                <!-- Botão de Enviar -->
                                <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    CADASTRAR
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Movimentações Recentes -->
                    <div id="movimentacoes-recentes" class="bg-white p-4 rounded-lg shadow-lg">
                        <h2 class="text-xl font-semibold mb-4 text-black-700">MOVIMENTAÇÕES RECENTES</h2>
                        @forelse($movimentacoes as $movimentacao)
                            <div class="text-black mb-4">
                                <p class="font-semibold">{{ $movimentacao->aluno->nome }}</p>
                                <p class="text-gray-600">{{ $movimentacao->tipo == 'entrada' ? 'Entrada' : 'Saída' }}</p>
                                <p class="text-gray-600">
                                    {{ $movimentacao->permissao ? 'Autorizado' : 'Pendente' }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-600">Nenhuma movimentação recente no momento!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const alunoModal = document.getElementById('alunoModal');
        const alunoClose = document.getElementById('alunoClose');
        const buscarAluno = document.getElementById('buscarAluno');
        const buscarMatricula = document.getElementById('buscarMatricula');

        async function buscarAlunoPelaMatricula() {
            const matricula = buscarMatricula.value.trim();
            if (!matricula) {
                alert('Por favor, insira a matrícula.');
                return;
            }

            try {
                const response = await fetch(`/api/alunos/${matricula}`);
                if (!response.ok) {
                    throw new Error('Aluno não encontrado.');
                }

                const aluno = await response.json();
                abrirAlunoModal(aluno);
            } catch (error) {
                alert(error.message);
            }
        }

        function abrirAlunoModal(info) {
            document.getElementById('alunoNome').textContent = info.nome || 'Não informado';
            document.getElementById('alunoMatricula').textContent = info.matricula || 'Não informado';
            document.getElementById('alunoIdade').textContent = info.idade || 'Não informado';
            document.getElementById('alunoResponsavel').textContent = info.responsavel || 'Não informado';
            document.getElementById('alunoCurso').textContent = info.curso || 'Não informado';

            alunoModal.classList.remove('hidden');
        }

        buscarAluno.addEventListener('click', buscarAlunoPelaMatricula);

        alunoClose.addEventListener('click', function () {
            alunoModal.classList.add('hidden');
        });
    </script>
</x-app-layout>
