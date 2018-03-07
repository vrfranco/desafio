<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplicativo do Desafio</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/prism.css') }}">
</head>
<body class="base">

    <div class="aplicativo" id="app">
        <div class="pagina">
            <transition name="fade">
                <router-view></router-view>
            </transition>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    
</body>
</html>