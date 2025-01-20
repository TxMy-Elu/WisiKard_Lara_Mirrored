<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Inscription</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-white md:bg-zinc-900">
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md p-4 mx-4 bg-white md:bg-white md:rounded-[30px] md:shadow-red">
        <div class="headerLogo flex justify-center items-center">
            <img src="{{ asset('images/WisiKardLogoBlack.png') }}" alt="Logo WisiKard" class="w-32 md:w-48 lg:w-96">
        </div>
        <div class="justify-center mt-10">
            <h1 class="text-center text-lg md:text-xl lg:text-2xl font-bold">Inscription Wisikard</h1>
            <div class="mt-10">
                <form action="{{ route('inscriptionEmploye.post') }}" method="post">
                    @csrf
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <input type="email" name="email" id="email"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <div class="mb-6">
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" name="nom" id="nom"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <div class="mb-6">
                        <label for="prenom" class="block text-sm font-medium text-gray-700">Prenom</label>
                        <input type="text" name="prenom" id="prenom"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <div class="mb-6">
                        <label for="tel" class="block text-sm font-medium text-gray-700">N° telephone</label>
                        <input type="text" name="tel" id="tel"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <div class="mb-4">
                        <label for="fonction" class="block text-gray-700 text-sm mb-2">Fonction:</label>
                        <input type="text" id="fonction" name="fonction" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-6">
                        @include('messageErreur')
                        <div class="mb-4">
                            <button type="submit" name="boutonInscription"
                                    class="w-full bg-red-900 text-white p-2 rounded-md">Inscription
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
