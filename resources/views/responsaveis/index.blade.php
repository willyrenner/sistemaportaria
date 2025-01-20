<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start ">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Menu de Responsáveis') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="bg-green-500 text-white px-4 py-2 mt-4 rounded hover:bg-green-600 inline-block">
                Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white text-white overflow-hidden shadow-lg sm:rounded-lg p-6">
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

                <div class="bg-white text-black p-4 rounded shadow-lg mb-6">
                    <h2 class="text-2xl font-semibold mb-4">Importar Responsáveis (Excel)</h2>
                    <form action="{{ route('responsaveis.import') }}" class="flex justify-center items-center"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- <input type="file" name="arquivo_excel" accept=".xlsx, .xls"
                            class="w-full px-3 py-2 rounded text-black border mr-4" required> -->
                        <input type="file" name="arquivo_excel" accept=".xlsx, .xls" class="block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0
                            file:font file:bg-green-500 file:text-white hover:file:bg-green-600" required>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Importar
                        </button>
                    </form>
                </div>

                <button onclick="toggleForm()"
                    class="w-full bg-green-500 px-4 py-2 rounded mb-6 hover:bg-green-600">Novo
                    Cadastro</button>

                <div id="cadastro-responsavel" class="text-black p-4 rounded shadow-lg mb-6 hidden">
                    <h2 class="text-2xl text-black font-semibold mb-4">CADASTRO DE RESPONSÁVEL</h2>



                    <form action="{{ route('responsaveis.store') }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <input type="text" name="nome" placeholder="NOME DO RESPONSÁVEL"
                                class="w-full px-3 py-2 rounded text-black" required>

                            <input type="text" name="telefone" placeholder="TELEFONE DO RESPONSÁVEL"
                                class="w-full px-3 py-2 rounded text-black" required>

                            <button type="submit"
                                class="w-full text-white bg-green-500 px-4 py-2 rounded hover:bg-green-600">
                                CADASTRAR
                            </button>
                        </div>
                    </form>
                </div>

                <div id="lista-responsaveis" class="bg-white text-black p-4 rounded shadow-lg">
                    <h2 class="text-2xl font-semibold mb-4">Responsáveis Cadastrados</h2>
                    <table class="table-auto w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-green-600 text-white">
                                <th class="px-4 py-2 text-left border">Nome</th>
                                <th class="px-4 py-2 text-left border">Telefone</th>
                                <th class="px-4 py-2 text-left border w-[200px]">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($responsaveis as $responsavel)
                                <tr class="odd:bg-gray-100 even:bg-gray-50 hover:bg-green-100">
                                    <td class="px-4 py-2 border">{{ $responsavel->nome }}</td>
                                    <td class="px-4 py-2 border">{{ $responsavel->telefone }}</td>
                                    <td class="px-4 py-2 border flex space-x-2 justify-start">
                                        <button onclick="toggleEditForm({{ $responsavel->id }})"
                                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                            Editar
                                        </button>

                                        <form action="{{ route('responsaveis.destroy', $responsavel->id) }}" method="POST"
                                            class="inline-block ml-2"
                                            onsubmit="event.preventDefault(); confirmDelete(this, '{{ $responsavel->nome }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 px-4 py-2 rounded text-white hover:bg-red-600">Excluir</button>
                                        </form>
                                    </td>
                                </tr>

                                <tr id="edit-form-{{ $responsavel->id }}" style="display: none;">
                                    <td colspan="3" class="px-4 py-2 border">
                                        <form action="{{ route('responsaveis.update', $responsavel->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="space-y-3">
                                                <input type="text" name="nome" value="{{ $responsavel->nome }}"
                                                    class="w-full px-3 py-2 rounded text-black" required>

                                                <input type="text" name="telefone" value="{{ $responsavel->telefone }}"
                                                    class="w-full px-3 py-2 rounded text-black" required>

                                                <button type="submit"
                                                    class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                                    Atualizar
                                                </button>

                                                <button type="button" onclick="toggleEditForm({{ $responsavel->id }})"
                                                    class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                                    Cancelar
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="confirmModal"
                    class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden backdrop-filter backdrop-blur-sm">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                        <h2 class="text-xl text-black font-bold mb-4 text-center" id="confirmMessage">Confirmando...
                        </h2>
                        <div class="flex justify-between">
                            <button id="confirmOk" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
                                Sim
                            </button>
                            <button id="confirmCancel" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-500">
                                Não
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/responsaveis.js') }}"></script>
</x-app-layout>
