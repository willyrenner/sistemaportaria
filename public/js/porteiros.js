let deleteForm;

function confirmDelete(form, porteiroNome) {
    deleteForm = form; // Salva o formulário que será enviado
    const modal = document.getElementById("confirmModal");
    const message = document.getElementById("confirmMessage");
    message.textContent = `Tem certeza de que deseja excluir o porteiro ${porteiroNome}?`;
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

let resetPassword;

function confirmPassword(form, porteiroNome) {
    resetPassword = form; // Salva o formulário que será enviado
    const modal = document.getElementById("confirmModal");
    const message = document.getElementById("confirmMessage");
    message.textContent = `Tem certeza de que deseja resetar a senha do porteiro ${porteiroNome}?`;
    modal.classList.remove("hidden");
}

// Fecha o modal sem realizar a ação
document.getElementById("confirmCancel").addEventListener("click", function () {
    const modal = document.getElementById("confirmModal");
    modal.classList.add("hidden");
});

// Confirma a exclusão e envia o formulário
document.getElementById("confirmOk").addEventListener("click", function () {
    if (resetPassword) {
        resetPassword.submit(); // Submete o formulário de exclusão
    }
});

// Função para alternar a exibição do formulário de edição
function toggleEditForm(id) {
    var form = document.getElementById("edit-form-" + id);
    form.classList.toggle("hidden");
}

// Função para exibir o formulário de cadastro
function toggleCadastroForm() {
    var form = document.getElementById("cadastro-porteiros");
    form.classList.toggle("hidden");
}

function limitInputLength(input, maxLength) {
    if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength); // Limita os caracteres
    }
}
