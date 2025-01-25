function handleInputChange(input) {
    if (input.value.trim() === "") {
        input.form.submit(); // Submete o formulário automaticamente se o campo for limpo
    }
}
