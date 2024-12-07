<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Portaria
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
</head>

<body class="bg-gray-900 min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="bg-gray-800 p-4 shadow-lg">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex items-center">
                <img alt="Logo" class="h-10 w-10 mr-3" height="40"
                    src="https://ead.ifrn.edu.br/portal/wp-content/uploads/2019/03/2000px-Logotipo_IFET.svg_-764x1024.png"
                    width="40" />
                <span class="text-white text-xl font-semibold">
                    Portaria - Campus Caicó
                </span>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg text-center w-full max-w-sm mx-4">
            <h1 class="text-2xl font-semibold text-white mb-2">
                Seja Bem-Vindo
            </h1>
            <p class="text-gray-400 mb-6">
                Você não está autenticado
            </p>
            <a class="block w-full bg-blue-600 text-white py-2 rounded mb-4" href="{{ route('login') }}">
                Entrar
            </a>
            <a class="block w-full bg-green-600 text-white py-2 rounded" id="suap-login-button">
                Fazer Login Suap
            </a>
        </div>
    </main>
</body>
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

</html>