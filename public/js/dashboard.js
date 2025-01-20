const alunoModal = document.getElementById("alunoModal");
const alunoClose = document.getElementById("alunoClose");
const buscarAluno = document.getElementById("buscarAluno");
const buscarMatricula = document.getElementById("buscarMatricula");

function limitInputLength(input, maxLength) {
    if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength); // Limita os caracteres
    }
}

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

    alunoModal.classList.remove("hidden");
}

buscarAluno.addEventListener("click", buscarAlunoPelaMatricula);

alunoClose.addEventListener("click", function () {
    alunoModal.classList.add("hidden");
});
