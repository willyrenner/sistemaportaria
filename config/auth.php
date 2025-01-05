<?php

return [

    /*
    |----------------------------------------------------------------------
    | Authentication Defaults
    |----------------------------------------------------------------------
    |
    | Esta opção controla o "guard" de autenticação padrão e as opções
    | de redefinição de senha para sua aplicação. Você pode mudar esses
    | padrões conforme necessário, mas eles são um ótimo ponto de partida
    | para a maioria das aplicações.
    |
    */

    'defaults' => [
        'guard' => 'web',  // Usar o guard web como padrão para usuários comuns
        'passwords' => 'users',
    ],

    /*
    |----------------------------------------------------------------------
    | Authentication Guards
    |----------------------------------------------------------------------
    |
    | Aqui você pode definir todos os guards de autenticação para sua aplicação.
    | Cada "guard" define como a autenticação será tratada. Para o porteiro,
    | um guard customizado foi adicionado.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',  // Usado para autenticação de usuários padrão
        ],

        'porteiro' => [
            'driver' => 'session',
            'provider' => 'porteiros',  // Usado para autenticação de porteiros
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | User Providers
    |----------------------------------------------------------------------
    |
    | Aqui você define como os usuários serão recuperados do banco de dados.
    | Para o porteiro, criamos um provider específico.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,  // Modelo de usuários padrão
        ],

        // Provider específico para os porteiros
        'porteiros' => [
            'driver' => 'eloquent',  // Ou 'database' dependendo da sua implementação
            'model' => App\Models\Porteiro::class,  // Modelo do porteiro, que você deve criar
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Resetting Passwords
    |----------------------------------------------------------------------
    |
    | Você pode configurar múltiplos resets de senha caso tenha mais de um
    | tipo de usuário. Isso permite ter configurações separadas para os
    | resets de senha de usuários padrão e porteiros.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        // Configuração de redefinição de senha para porteiros
        'porteiros' => [
            'provider' => 'porteiros',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Password Confirmation Timeout
    |----------------------------------------------------------------------
    |
    | Este valor define o tempo de expiração da confirmação de senha.
    |
    */

    'password_timeout' => 10800,

];
