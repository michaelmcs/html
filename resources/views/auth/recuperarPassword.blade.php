<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @foreach ($datos as $item)
        <title>{{ $item->nombre }}</title>
        <link rel="shortcut icon" href="{{asset("foto/empresa/$item->foto")}}" type="image/x-icon">
    @endforeach

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/646ac4fad6.js" crossorigin="anonymous"></script>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        body {
            background-size: cover;
            background-image: url({{ asset('img/fondo2.jpg') }});
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .logo {
            width: 120px;
        }

        .container {
            width: 400px;
            padding: 40px;
            border-radius: 15px;
            background: rgba(216, 192, 192, 0.785);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 28px;
            box-shadow: 0px 0px 8px rgb(255, 0, 0);
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: black;
            text-shadow: 0px 0px 10px white;
        }

        .entrada,
        .olvide,
        .salir {
            padding: 10px 20px;
            border: none;
            color: white;
            width: 100%;
            text-decoration: none;
            text-align: center;
        }

        .entrada {
            background: rgb(0, 102, 255);
            margin-bottom: 5px;
        }

        .salir {
            width: 80px;
            text-align: center;
            background: rgb(211, 7, 0);
            padding: 5px 8px;
        }

        .olvide {
            background: rgb(0, 174, 23);
        }

        .entrada:hover {
            background: rgb(2, 95, 236);
        }

        .olvide:hover {
            color: white;
            background: rgb(1, 159, 22);
        }

        .salir:hover {
            color: white;
            background: rgb(174, 6, 0);
        }

        input {
            padding: 10px !important;
        }

        .copy,
        .copy a {
            color: black;
        }

        .alert-success {
            padding: 5px 10px;
            color: white;
            background: green;
        }

        .alert-error {
            padding: 5px 10px;
            color: white;
            background: rgb(255, 52, 52);
        }

        @media screen and (max-height:512px) {
            .container {
                margin-top: 140px;
            }
        }
    </style>

</head>

<body>
    <div class="container">
        
        <div class="text-left w-100">
            <a class="salir" href="{{ route('welcome') }}" type="submit"><i class="fa-solid fa-caret-left"></i>
                Volver</a>
        </div>

        @if (session('CORRECTO'))
            <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
        @endif

        @if (session('INCORRECTO'))
            <div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
        @endif
        <div>
            @foreach ($datos as $item)
                <img class="logo" src="{{asset("foto/empresa/$item->foto")}}" alt="">
            @endforeach
        </div>
        <div>
            <form action="{{ route('recuperar.enviar') }}" method="POST">
                @csrf
                <h2 class="title text-center">Recuperar contraseña</h2>

                <input type="text" placeholder="DNI" class="form-control mb-1" name="dni"
                    value="{{ old('dni') }}">
                <input type="email" placeholder="Correo electrónico" class="form-control mb-1" name="correo"
                    value="{{ old('correo') }}">

                @error('dni')
                    <small class="error-message text-white bg-danger">{{ $message }}</small><br>
                @enderror
                @error('correo')
                    <small class="error-message text-white bg-danger">{{ $message }}</small>
                @enderror

                <button class="entrada" type="submit"><i class="fa-solid fa-right-from-bracket"></i> Enviar codigo a mi
                    correo</button>
                <a class="olvide" href="{{ route('acceso') }}" type="submit"><i class="fa-solid fa-lock"></i> Iniciar
                    sesion</a>
            </form>
        </div>
        <div>
            <p class="copy">Copyright © {{ date('Y') }} </p>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>
