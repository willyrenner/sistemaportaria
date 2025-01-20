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
            // form.method = 'POST';
            form.method = 'HEAD';
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

// const form = document.querySelector('form[action="{{ route('registros.store') }}"]');
const form = document.querySelector('form[data-action]');

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
