function limitInputLength(input, maxLength) {
    if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength); // Limita os caracteres
    }
}

const confirmModal = document.getElementById("confirmModal");
const confirmMessage = document.getElementById("confirmMessage");
const confirmOk = document.getElementById("confirmOk");
const confirmCancel = document.getElementById("confirmCancel");
const alunoForm = document.getElementById("aluno-form");

alunoForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const matricula = alunoForm.querySelector('input[name="matricula"]').value;
    const tipo = alunoForm.querySelector('select[name="tipo"]').value;

    fetch(`/alunos/${matricula}`)
        .then((response) => {
            if (!response.ok) throw new Error("Aluno não encontrado");
            return response.json();
        })
        .then((data) => {
            confirmMessage.textContent = `Confirmar ${
                tipo === "entrada" ? "entrada" : "saída"
            } de ${data.nome} (${data.matricula})?`;

            confirmModal.classList.remove("hidden");

            confirmOk.onclick = () => {
                confirmModal.classList.add("hidden");
                alunoForm.submit();
            };

            confirmCancel.onclick = () => {
                confirmModal.classList.add("hidden");
            };
        })
        .catch((error) => {
            const errorDiv = document.getElementById("error-message-aluno");

            // Define a mensagem de erro
            errorDiv.textContent = error.message;

            // Remove a classe hidden para exibir a mensagem
            errorDiv.classList.remove("hidden");
        });
});

const visitorForm = document.getElementById("visitor-form");
const visitorConfirmModal = document.getElementById("confirmModal");
const visitorConfirmMessage = document.getElementById("confirmMessage");
const visitorConfirmOk = document.getElementById("confirmOk");
const visitorConfirmCancel = document.getElementById("confirmCancel");
const cpfInput = document.getElementById("cpf");

visitorForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const nome = visitorForm.querySelector('input[name="nome"]').value;
    const tipo = visitorForm.querySelector('input[name="tipo"]').value;

    visitorConfirmMessage.textContent = `Confirmar ${
        tipo === "entrada" ? "entrada" : "saída"
    } do visitante ${nome}?`;

    // Exibe o modal de confirmação
    visitorConfirmModal.classList.remove("hidden");

    // Confirmação
    visitorConfirmOk.onclick = () => {
        visitorConfirmModal.classList.add("hidden");
        visitorForm.submit();
    };

    // Cancelamento
    visitorConfirmCancel.onclick = () => {
        visitorConfirmModal.classList.add("hidden");
    };
});

// Capturar botões "Autorizar Saída"
document.querySelectorAll(".autorizar-saida").forEach((button) => {
    button.addEventListener("click", function () {
        const nome = button.getAttribute("data-nome");
        const registroId = button.getAttribute("data-registro-id");

        // Configura o modal
        confirmMessage.textContent = `Confirmar saída do visitante ${nome}?`;
        confirmModal.classList.remove("hidden");

        // Confirmar ação
        confirmOk.onclick = () => {
            confirmModal.classList.add("hidden");

            // Cria um formulário temporário para enviar a requisição
            const form = document.createElement("form");
            form.method = "POST";
            form.action = `/porteiro/confirmar-saida-visitante/${registroId}`;

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "PUT";
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        };

        // Cancelar ação
        confirmCancel.onclick = () => {
            confirmModal.classList.add("hidden");
        };
    });
});

const alunoModal = document.getElementById("alunoModal");
const alunoClose = document.getElementById("alunoClose");
const buscarAluno = document.getElementById("buscarAluno");
const buscarMatricula = document.getElementById("buscarMatricula");

// Função para abrir o modal com informações do aluno
function abrirAlunoModal(info) {
    document.getElementById("alunoNome").textContent =
        info.nome || "Não informado";
    document.getElementById("alunoMatricula").textContent =
        info.matricula || "Não informado";
    document.getElementById("alunoIdade").textContent =
        info.idade || "Não informado";
    document.getElementById("alunoResponsavel").textContent =
        info.responsavel || "Não informado";
    document.getElementById("alunoCurso").textContent =
        info.curso || "Não informado";

    alunoModal.classList.remove("hidden"); // Mostra o modal
}

// Função para buscar o aluno pela matrícula
async function buscarAlunoPelaMatricula() {
    const matricula = buscarMatricula.value.trim();
    if (!matricula) {
        const errorDiv = document.getElementById("error-message");
        errorDiv.textContent = "Por favor, insira a matrícula.";
        errorDiv.classList.remove("hidden");
        return;
    } else {
        const errorDiv = document.getElementById("error-message");
        errorDiv.classList.add("hidden");
    }

    try {
        const response = await fetch(`/api/alunos/${matricula}`);
        if (!response.ok) {
            throw new Error("Aluno não encontrado.");
        }

        const aluno = await response.json();
        abrirAlunoModal(aluno);
    } catch (error) {
        // Seleciona a div de erro
        const errorDiv = document.getElementById("error-message");

        // Define a mensagem de erro
        errorDiv.textContent = error.message;

        // Remove a classe hidden para exibir a mensagem
        errorDiv.classList.remove("hidden");
    }
}

// Adicionar evento ao botão de busca
buscarAluno.addEventListener("click", buscarAlunoPelaMatricula);

// Fechar o modal ao clicar no botão "Fechar"
alunoClose.addEventListener("click", function () {
    alunoModal.classList.add("hidden"); // Esconde o modal
});
