<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Portaria
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilos personalizados para o logo */
        #containerLogo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        #p {
            font-size: 18px;
            font-weight: bold;
            color: #1d4ed8;
        }

        /* Personalização para o background */
        #body {
            background: linear-gradient(to bottom, #144444 20%, #18746c 100%);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
    <link rel="icon" href="{{ asset('img/ifrn-logo.ico') }}" type="image/x-icon">
</head>

<body id="body" class="flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-lg w-80 p-8">
        <div id="containerLogo">
            <img src="https://ead.ifrn.edu.br/portal/wp-content/uploads/2019/03/2000px-Logotipo_IFET.svg_-764x1024.png"
                alt="Logo IFRN" class="w-24 mb-4" />
            <!-- <h1 id="p" class="text-center text-lg text-gray-700">Portaria - Campus Caicó</h1> -->
        </div>

        <h1 class="text-xl text-center text-gray-700 font-semibold mb-6">Controle de Entrada/Saída</h1>

        <h1 class="text-2xl text-center text-gray-700 font-semibold mb-6">Seja Bem-Vindo</h1>

        <div id="local-error" class="bg-red-500 text-white p-4 rounded mb-4" hidden></div>

        <script>
            const localError = localStorage.getItem('error');
            if (localError) {
                const errorDiv = document.getElementById('local-error');

                errorDiv.removeAttribute('hidden');

                errorDiv.textContent = localError;

                localStorage.removeItem('error');
            }
        </script>


        <!-- Botões de ação -->
        <a class="block w-full bg-green-600 text-white py-2 rounded text-center mb-4" id="suap-login-button">
            Login com SUAP
        </a>
        <a class="block w-full bg-blue-600 text-white py-2 rounded text-center" href="{{ route('autenticar') }}">
            Entrar
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/js.cookie.js"></script>
    <script src="js/client.js"></script>
    <script src="js/settings.js"></script>
    <script>
        var suap = new SuapClient(
            SUAP_URL, CLIENT_ID, HOME_URI, REDIRECT_URI, SCOPE
        );
        suap.init();
        $(document).ready(function () {
            $("#suap-login-button").attr('href', suap.getLoginURL());
        });
    </script>

</body>

</html>