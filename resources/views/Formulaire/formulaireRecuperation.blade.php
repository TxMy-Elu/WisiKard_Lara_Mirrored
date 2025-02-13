<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mot de passe oublié</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
</head>
<body class="bg-white dark:bg-zinc-900 min-h-screen flex justify-center items-center">
<div class="w-full max-w-md p-6 mx-4 bg-white rounded-2xl shadow-lg">
    <div class="flex justify-center items-center mb-10">
        <img src="{{ asset('images/WisiKardLogoBlack.png') }}" alt="Logo WisiKard" class="w-32 md:w-48 lg:w-96">
    </div>
    <h1 class="text-center text-lg font-bold mb-6 md:text-xl lg:text-2xl">Mot de passe oublié</h1>
    <form method="POST" action="{{ route('validationEmailMotDePasseOublie') }}">
        @csrf
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700">
                Entrez l'adresse email associée à votre compte :
            </label>
            <input
                    type="email"
                    name="email"
                    id="email"
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required
            >
        </div>

        @include('messageErreur')

        <button
                type="submit"
                name="boutonRecuperer"
                class="w-full bg-red-900 text-white p-2 rounded-md hover:bg-red-700">
            Valider
        </button>
    </form>
</div>
</body>
</html>