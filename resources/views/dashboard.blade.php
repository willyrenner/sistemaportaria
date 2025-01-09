<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel COAPAC') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div id="dashboard" class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Menu -->
                    <div id="menu" class="bg-gray-800 p-4 rounded shadow-lg">
                        <h2 class="text-2xl font-semibold mb-4">MENU</h2>
                        <ul class="space-y-2">
                            <li><a href="/autorizar-menores" class="text-lg block hover:text-green-400">AUTORIZAR SAÍDA DE MENORES</a>
                            </li>
                            <li><a href="/registros" class="text-lg block hover:text-green-400">HISTÓRICO DE
                                    SAÍDAS/ENTRADAS</a>
                            </li>
                            <li><a href="/porteiros" class="text-lg block hover:text-green-400">MENU PORTEIROS</a>
                            </li>
                            <li><a href="/alunos" class="text-lg block hover:text-green-400">MENU ALUNO</a>
                            </li>
                            <li><a href="/cursos" class="text-lg block hover:text-green-400">MENU CURSOS</a>
                            </li>
                            <li><a href="/responsaveis" class="text-lg block hover:text-green-400">MENU
                                    RESPONSÁVEIS</a>
                            </li>
                        </ul>
                        <input type="text" class="mt-4 w-full px-3 py-2 rounded text-black" placeholder="BUSCAR ALUNO">
                        <button class="w-full mt-3 bg-green-500 px-4 py-2 rounded hover:bg-green-600">BUSCAR</button>
                    </div>

                    <!-- Cadastro de Porteiros -->
                    <div id="cadastro-porteiros" class="bg-gray-800 p-4 rounded shadow-lg">
                        <h2 class="text-2xl font-semibold mb-4">CADASTRO DE PORTEIROS</h2>
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
                                    <h4 class="font-semibold text-lg mb-2">TURNO</h4>
                                    <select name="turno" class="w-full px-3 py-2 rounded text-black">
                                        <option value="">Selecione o turno</option>
                                        <option value="matutino">MATUTINO</option>
                                        <option value="vespertino">VESPERTINO</option>
                                        <option value="noturno">NOTURNO</option>
                                    </select>
                                </div>

                                <!-- Botão de Enviar -->
                                <button type="submit" class="w-full bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                    CADASTRAR
                                </button>
                            </div>
                        </form>


                    </div>

                    <!-- Movimentações Recentes -->
                    <div id="movimentacoes-recentes" class="bg-gray-800 p-4 rounded shadow-lg">
                        <h2 class="text-2xl font-semibold mb-4">MOVIMENTAÇÕES RECENTES</h2>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <button class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600">DATA</button>
                            <button class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600">NOME</button>
                            <button class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600">SEXO</button>
                            <button class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600">+18/-18</button>
                            <button class="col-span-2 bg-blue-500 px-4 py-2 rounded hover:bg-blue-600">FILTRAR</button>
                        </div>
                        @foreach($movimentacoes as $movimentacao)
                            <div class="text-white-700 mb-4">
                                <p class="font-semibold">{{ $movimentacao->aluno->nome }}</p>
                                <p class="text-white-600">{{ $movimentacao->tipo == 'entrada' ? 'Entrada' : 'Saída' }}</p>
                                <p class="text-white-600">
                                    {{ $movimentacao->permissao ? 'Autorizado' : 'Pendente' }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>