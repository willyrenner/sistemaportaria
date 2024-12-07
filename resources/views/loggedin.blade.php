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
            window.location.href = '/dashboard'
        })
        .catch(error => {
            if (error.response) {
                // A resposta do servidor
                console.error('Erro no servidor:', error.response.data);
            } else if (error.request) {
                // O pedido foi feito, mas não houve resposta
                console.error('Erro no pedido:', error.request);
            } else {
                // Algo aconteceu na configuração do pedido
                console.error('Erro na configuração do pedido:', error.message);
            }
        });



</script>