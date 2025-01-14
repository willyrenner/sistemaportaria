<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Painel COAPAC') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-100 text-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div id="dashboard" class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Menu -->
                    <div id="menu" class="bg-white p-4 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-semibold mb-4 text-black-700">MENU</h2>
                        <ul class="space-y-2">
                            <li><a href="/autorizar-menores" class="text-lg block hover:text-green-500">AUTORIZAR SAÍDA
                                    DE MENORES</a></li>
                            <li><a href="/registros" class="text-lg block hover:text-green-500">HISTÓRICO DE
                                    SAÍDAS/ENTRADAS</a></li>
                            <li><a href="/registros-visitantes" class="text-lg block hover:text-green-500">HISTÓRICO DE
                                    VISITANTES</a></li>
                            <li><a href="/porteiros" class="text-lg block hover:text-green-500">MENU PORTEIROS</a></li>
                            <li><a href="/cursos" class="text-lg block hover:text-green-500">MENU CURSOS</a></li>
                            <li><a href="/responsaveis" class="text-lg block hover:text-green-500">MENU
                                    RESPONSÁVEIS</a></li>
                            <li><a href="/alunos" class="text-lg block hover:text-green-500">MENU ALUNO</a></li>
                        </ul>
                        <div class="flex flex-col mt-8">
                            <h1 class="text-xl font-semibold mb-2 text-black-700">BUSCAR ALUNO</h1>
                            <input type="text" id="buscarMatricula"
                                class="border border-gray-300 rounded flex-1 px-4 py-2"
                                placeholder="INFORME A MATRICULA">
                            <button id="buscarAluno"
                                class="bg-green-500 mt-4 text-white px-4 py-2 rounded hover:bg-green-600">BUSCAR</button>
                        </div>

                    </div>

                    <!-- Cadastro de Porteiros -->
                    <div id="cadastro-porteiros" class="bg-white p-4 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-semibold mb-4 text-black-700">CADASTRO DE PORTEIROS</h2>
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
                                <input type="text" name="name" placeholder="NOME"
                                    class="w-full px-3 py-2 rounded text-black" required>

                                <input type="hidden" name="email" value="">

                                <!-- Campo CPF -->
                                <input type="text" name="cpf" placeholder="CPF"
                                    class="w-full px-3 py-2 rounded text-black" required>

                                <!-- Campo Matrícula -->
                                <input type="text" name="matricula" placeholder="MATRÍCULA"
                                    class="w-full px-3 py-2 rounded text-black" required>

                                <!-- Campos Fixos -->
                                <input type="hidden" name="role" value="porteiro">

                                <!-- Campo Turnos -->
                                <div>
                                    <h4 class="font-semibold text-lg mb-2 text-black-700">TURNO</h4>
                                    <select name="turno" class="w-full px-3 py-2 rounded text-black">
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
                        <h2 class="text-2xl font-semibold mb-4 text-black-700">MOVIMENTAÇÕES RECENTES</h2>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <button class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">DATA</button>
                            <button class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">NOME</button>
                            <button class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">SEXO</button>
                            <button class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">+18/-18</button>
                            <button
                                class="col-span-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">FILTRAR</button>
                        </div>
                        @foreach($movimentacoes as $movimentacao)
                            <div class="text-black mb-4">
                                <p class="font-semibold">{{ $movimentacao->aluno->nome }}</p>
                                <p class="text-gray-600">{{ $movimentacao->tipo == 'entrada' ? 'Entrada' : 'Saída' }}</p>
                                <p class="text-gray-600">
                                    {{ $movimentacao->permissao ? 'Autorizado' : 'Pendente' }}
                                </p>
                            </div>
                        @endforeach
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
                                <button id="alunoClose"
                                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-500">
                                    Fechar
                                </button>
                            </div>
                        </div>
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

        // Função para abrir o modal com informações do aluno
        function abrirAlunoModal(info) {
            document.getElementById('alunoNome').textContent = info.nome || 'Não informado';
            document.getElementById('alunoMatricula').textContent = info.matricula || 'Não informado';
            document.getElementById('alunoIdade').textContent = info.idade || 'Não informado';
            document.getElementById('alunoResponsavel').textContent = info.responsavel || 'Não informado';
            document.getElementById('alunoCurso').textContent = info.curso || 'Não informado';

            alunoModal.classList.remove('hidden'); // Mostra o modal
        }

        // Função para buscar o aluno pela matrícula
        async function buscarAlunoPelaMatricula() {
            const matricula = buscarMatricula.value.trim();
            if (!matricula) {
                alert('Por favor, insira a matrícula.');
                return;
            }

            try {
                // Enviar requisição ao backend (ajuste a URL conforme necessário)
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

        // Adicionar evento ao botão de busca
        buscarAluno.addEventListener('click', buscarAlunoPelaMatricula);

        // Fechar o modal ao clicar no botão "Fechar"
        alunoClose.addEventListener('click', function () {
            alunoModal.classList.add('hidden'); // Esconde o modal
        });
    </script>
</x-app-layout>