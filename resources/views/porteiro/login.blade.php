<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="imagens/ifrn-logo.ico" type="image/x-icon">
    <title>LOGIN</title>
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
</head>

<body id="body" class="flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-lg shadow-lg w-80 p-8">
        <div id="containerLogo">
            <img src="img/ifrn-logo.png" alt="Logo IFRN" class="w-24 mb-4" width="">
            <h1 id="p" class="text-center text-lg text-gray-700">Portaria - Campus Caicó</h1>
        </div>

        <h1 class="text-2xl text-center text-gray-700 font-semibold mb-6">Login</h1>

        <form method="POST" action="{{ route('porteiro.login') }}">
            @csrf
            <!-- Campo de Usuário (Email ou Matrícula) -->
            <input type="text" name="matricula" id="input" class="w-full p-3 mb-4 border rounded-md border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Usuário" required>

            <!-- Campo de Senha -->
            <input type="password" name="password" id="input" class="w-full p-3 mb-6 border rounded-md border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Senha" required>

            <!-- Link para recuperação de senha -->
            <a href="#" id="link" class="text-sm text-blue-600 hover:underline block text-center mb-4">Esqueceu sua senha?</a>

            <!-- Botão de Login -->
            <button type="submit" id="button" class="w-full bg-blue-600 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Entrar
            </button>
        </form>
    </div>

</body>

</html>
