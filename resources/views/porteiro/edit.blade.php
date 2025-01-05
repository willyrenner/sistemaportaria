<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Porteiros Cadastrados') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-800 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-800 border border-gray-700">
                        <thead>
                            <tr class="bg-gray-700">
                                <th class="px-4 py-2 text-left border-b">Nome</th>
                                <th class="px-4 py-2 text-left border-b">Email</th>
                                <th class="px-4 py-2 text-left border-b">CPF</th>
                                <th class="px-4 py-2 text-left border-b">Matrícula</th>
                                <th class="px-4 py-2 text-left border-b">Turno</th> <!-- Coluna de Turno -->
                                <th class="px-4 py-2 text-left border-b">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(session('success'))
                                <div class="bg-green-500 text-white p-4 rounded mb-4">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="bg-red-500 text-white p-4 rounded mb-4">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @foreach($porteiros as $porteiro)
                                <tr class="hover:bg-gray-700 border-b">
                                    <td class="px-4 py-2">{{ $porteiro->name }}</td>
                                    <td class="px-4 py-2">{{ $porteiro->email }}</td>
                                    <td class="px-4 py-2">{{ $porteiro->cpf }}</td>
                                    <td class="px-4 py-2">{{ $porteiro->matricula }}</td>
                                    <td class="px-4 py-2">
                                        {{ $porteiro->turno ?? 'Nenhum' }} <!-- Exibindo o turno -->
                                    </td>
                                    <td class="px-4 py-2 flex space-x-2 justify-start">
                                        <button class="text-blue-500 hover:text-blue-700"
                                            onclick="toggleEditForm({{ $porteiro->id }})">Editar</button>

                                        <!-- Formulário para excluir o porteiro -->
                                        <form action="{{ route('porteiros.destroy', $porteiro->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este porteiro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <tr id="edit-form-{{ $porteiro->id }}" style="display: none;">
                                    <td colspan="6">
                                        <form action="{{ route('porteiros.update', $porteiro->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="space-y-4">
                                                <input type="text" name="name" value="{{ $porteiro->name }}"
                                                    class="w-full px-4 py-2 rounded text-black bg-white" required>
                                                <input type="email" name="email" value="{{ $porteiro->email }}"
                                                    class="w-full px-4 py-2 rounded text-black bg-white">
                                                <input type="text" name="cpf" value="{{ $porteiro->cpf }}"
                                                    class="w-full px-4 py-2 rounded text-black bg-white" required>
                                                <input type="text" name="matricula" value="{{ $porteiro->matricula }}"
                                                    class="w-full px-4 py-2 rounded text-black bg-white" required>
                                                
                                                <!-- Turno - Select Box com apenas um turno -->
                                                <div class="space-y-2">
                                                    <label class="block">
                                                        <input type="radio" name="turno" value="matutino" 
                                                            {{ $porteiro->turno === 'matutino' ? 'checked' : '' }} class="mr-2"> MATUTINO
                                                    </label>
                                                    <label class="block">
                                                        <input type="radio" name="turno" value="vespertino"
                                                            {{ $porteiro->turno === 'vespertino' ? 'checked' : '' }} class="mr-2"> VESPERTINO
                                                    </label>
                                                    <label class="block">
                                                        <input type="radio" name="turno" value="noturno"
                                                            {{ $porteiro->turno === 'noturno' ? 'checked' : '' }} class="mr-2"> NOTURNO
                                                    </label>
                                                </div>

                                                <button type="submit"
                                                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Salvar</button>
                                                <button type="button" onclick="toggleEditForm({{ $porteiro->id }})"
                                                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleEditForm(id) {
            var form = document.getElementById('edit-form-' + id);
            form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
</x-app-layout>
