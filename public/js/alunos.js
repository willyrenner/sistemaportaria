function copyId(matricula) {
    const texto = matricula;
    navigator.clipboard
        .writeText(texto)
        .then(() => {
            const errorDiv = document.getElementById("error-message");
            errorDiv.textContent = `Matrícula ${texto} copiada com sucesso!`;
            errorDiv.classList.remove("hidden");
        })
        .catch(() => {
            const errorDiv = document.getElementById("error-message");
            errorDiv.classList.add("hidden");
        });
}

let deleteForm; // Variável para armazenar o formulário de exclusão

// Exibe o modal de confirmação
function confirmDelete(form, alunoNome, alunoMatricula) {
    deleteForm = form; // Salva o formulário que será enviado
    const modal = document.getElementById("confirmModal");
    const message = document.getElementById("confirmMessage");
    message.textContent = `Tem certeza de que deseja excluir o aluno ${alunoNome} (${alunoMatricula}) ?`;
    modal.classList.remove("hidden");
}

// Fecha o modal sem realizar a ação
document.getElementById("confirmCancel").addEventListener("click", function () {
    const modal = document.getElementById("confirmModal");
    modal.classList.add("hidden");
});

// Confirma a exclusão e envia o formulário
document.getElementById("confirmOk").addEventListener("click", function () {
    if (deleteForm) {
        deleteForm.submit(); // Submete o formulário de exclusão
    }
});

function toggleForm() {
    const form = document.getElementById("cadastro-aluno");
    form.classList.toggle("hidden");
}

function showEditForm(id) {
    const form = document.getElementById(`edit-form-${id}`);
    form.classList.toggle("hidden");
}

function handleInputChange(input) {
    if (input.value.trim() === "") {
        input.form.submit(); // Submete o formulário automaticamente se o campo for limpo
    }
}
