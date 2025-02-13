<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Confirmation</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
</head>
<body class="flex flex-col items-center w-full">
@include('menuPrincipal')

<main class="flex flex-col items-center w-full">
    <form method="POST" action="{{ route('validationChangementMotDePasse') }}" class="bg-white shadow-md rounded w-1/4 mx-auto mt-20 mb-20 p-5">
        @csrf
        <div class="flex flex-col items-center text-center">
            <h1 class="mb-3 text-lg font-bold">
                Confirmation
            </h1>
            <div class="mb-3 p-3 bg-green-100 text-green-700 text-left rounded w-full">
                <b>{{ $messageConfirmation }}</b>
            </div>
            <div>
                <a href="{{ route('connexion') }}" class="text-blue-500 hover:underline">Se connecter</a>
            </div>
        </div>
    </form>
</main>
</body>
</html>