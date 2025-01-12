<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // Função para pegar o valor de um parâmetro na URL (fragmento)
    function getTokenFromUrl() {
        // Pega a parte da URL após o '#'
        var hash = window.location.hash;
        // Cria um objeto URLSearchParams a partir da string do hash
        var params = new URLSearchParams(hash.substring(1)); // Remove o '#' do início
        // Pega o valor do parâmetro 'access_token'
        var token = params.get('access_token');
        return token;
    }

    // Exemplo de como usar
    // Envia o token para o backend via AJAX usando fetch
    var token = getTokenFromUrl();

    axios.post('/callback', { token: token })
        .then(response => {
            console.log('Token enviado com sucesso:', response.data);
            window.location.href = '/dashboard'; // Redireciona em caso de sucesso
        })
        .catch(error => {
            let errorMessage = '';

            if (error.response) {
                errorMessage = error.response.data.error;
            } else if (error.request) {
                errorMessage = 'Erro no pedido. O servidor não respondeu.';
            } else {
                errorMessage = 'Erro na configuração do pedido: ' + error.message;
            }

            // Salva a mensagem de erro no Local Storage
            localStorage.setItem('error', errorMessage);

            // Redireciona para a página inicial
            window.location.href = '/';
        });

</script>