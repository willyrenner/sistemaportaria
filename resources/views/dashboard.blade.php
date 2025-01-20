<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-center">
            {{ __('Painel COAPAC') }}
        </h2>
    </x-slot>

    <div id="dashboard" class="grid grid-cols-1 gap-6 md:grid-cols-3 p-6">
        <!-- Menu -->
        <div class="row-span-2 flex flex-col bg-white shadow-lg p-6 rounded-lg col-span-1">
            <h2 class="text-xl font-semibold mb-4 text-black-700">MENU</h2>
            <ul class="space-y-2">
                <li><a href="/autorizar-menores" class="text-lg block hover:text-green-500">AUTORIZAR SAÍDA
                        DE MENORES</a></li>
                <li><a href="/registros" class="text-lg block hover:text-green-500">HISTÓRICO DE
                        SAÍDAS/ENTRADAS</a></li>
                <li><a href="/registros-visitantes" class="text-lg block hover:text-green-500">HISTÓRICO DE
                        VISITANTES</a></li>
                <li><a href="/porteiros" class="text-lg block hover:text-green-500">MENU PORTEIROS</a></li>
                <li><a href="/cursos" class="text-lg block hover:text-green-500">MENU CURSOS</a></li>
                <li><a href="/responsaveis" class="text-lg block hover:text-green-500">MENU RESPONSÁVEIS</a>
                </li>
                <li><a href="/alunos" class="text-lg block hover:text-green-500">MENU ALUNO</a></li>
            </ul>
            <div class="flex flex-col mt-8">
                <h1 class="text-lg font-semibold mb-2 text-black-700">BUSCAR ALUNO</h1>
                <div id="error-message" class="bg-red-500 text-white p-4 rounded mb-4 hidden"></div>

                <input type="number" id="buscarMatricula" class="border border-gray-300 rounded w-full px-4 py-2 mb-2"
                    placeholder="INFORME A MATRICULA" oninput="limitInputLength(this, 14)">
                <button id="buscarAluno"
                    class="bg-green-500 w-full text-white px-4 py-2 rounded hover:bg-green-600">BUSCAR</button>
            </div>
        </div>

        <!-- Cadastro de Porteiros -->
        <div id="cadastro-porteiros"
            class="flex flex-col items-center bg-white shadow-lg p-6 rounded-lg col-span-1 row-span-2">
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
                    <input type="text" name="name" placeholder="NOME"
                        class="w-full px-3 py-2 rounded text-black border border-gray-300" required>

                    <input type="hidden" name="email" value="">

                    <!-- Campo CPF -->
                    <input type="number" name="cpf" placeholder="CPF"
                        class="w-full px-3 py-2 rounded text-black border border-gray-300"
                        oninput="limitInputLength(this, 11)" required>

                    <!-- Campo Matrícula -->
                    <input type="text" name="matricula" placeholder="MATRÍCULA"
                        class="w-full px-3 py-2 rounded text-black border border-gray-300" required>

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
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
</x-app-layout>
