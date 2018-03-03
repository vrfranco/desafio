<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Autorização do Desafio</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="base">
    <div class="autenticacao">

        <form action="{{ route('authenticate') }}" method="post" class="formulario @if( $errors->has('auth') ) erro @endif">

            @csrf

            <div class="cabecalho">
                <div class="destaque">Autenticação</div>
                <div class="descricao">do desafio</div>
            </div>

            <div class="campos">
                <div class="campo @if( $errors->has('password') ) erro @endif">
                    <div class="icone email"></div>
                    <input class="texto" type="text" name="email" value="{{ old('email') }}" placeholder="EMAIL DE ACESSO" autofocus>

                    @if( $errors->has('email') )
                        <div class="mensagem erro">
                            @foreach ($errors->get('email') as $message)
                                {{ $message }}
                            @endforeach
                        </div>
                    @endif

                </div>

                <div class="campo @if( $errors->has('password') ) erro @endif">
                    <div class="icone senha"></div>
                    <input class="texto" type="password" name="password" value="{{ old('password') }}" placeholder="SENHA">

                    @if( $errors->has('password') )
                        <div class="mensagem erro">
                            @foreach ($errors->get('password') as $message)
                                {{ $message }}
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            @if( $errors->has('auth') )
                <div class="mensagens erro">
                    @foreach ($errors->get('auth') as $message)
                        {{ $message }}
                    @endforeach
                </div>
            @endif

            <div class="rodape">
                <button class="botao" type="submit">Entrar</button>
            </div>

        </form>
    </div>    
</body>
</html>