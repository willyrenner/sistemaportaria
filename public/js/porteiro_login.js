document.addEventListener("DOMContentLoaded", () => {
    const forgotPasswordLink = document.getElementById("link");
    const modal = document.getElementById("confirmModal");
    const confirmOk = document.getElementById("confirmOk");
    const confirmCancel = document.getElementById("confirmCancel");

    // Mostrar o modal ao clicar no link
    forgotPasswordLink.addEventListener("click", (e) => {
        e.preventDefault(); // Evitar comportamento padrão do link
        modal.classList.remove("hidden"); // Exibir o modal
    });

    // Fechar o modal ao clicar no botão "Ok"
    confirmOk.addEventListener("click", () => {
        modal.classList.add("hidden"); // Esconder o modal
    });

    // Fechar o modal ao clicar no botão "Cancelar"
    confirmCancel.addEventListener("click", () => {
        modal.classList.add("hidden"); // Esconder o modal
    });
});
