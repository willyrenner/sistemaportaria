let deleteForm; // Variável para armazenar o formulário de exclusão

        // Exibe o modal de confirmação
        function confirmDelete(form, responsavelNome) {
            deleteForm = form; // Salva o formulário que será enviado
            const modal = document.getElementById('confirmModal');
            const message = document.getElementById('confirmMessage');
            message.textContent = `Tem certeza de que deseja excluir o responsável ${responsavelNome}?`;
            modal.classList.remove('hidden');
        }

        // Fecha o modal sem realizar a ação
        document.getElementById('confirmCancel').addEventListener('click', function () {
            const modal = document.getElementById('confirmModal');
            modal.classList.add('hidden');
        });

        // Confirma a exclusão e envia o formulário
        document.getElementById('confirmOk').addEventListener('click', function () {
            if (deleteForm) {
                deleteForm.submit(); // Submete o formulário de exclusão
            }
        });


        function toggleForm() {
            const form = document.getElementById('cadastro-responsavel');
            form.classList.toggle('hidden');
        }

        function toggleEditForm(id) {
            var form = document.getElementById('edit-form-' + id);
            form.style.display = form.style.display === 'none' ? 'table-row' : 'none';
        }
