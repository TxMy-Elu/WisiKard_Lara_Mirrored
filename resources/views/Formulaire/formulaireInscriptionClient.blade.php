<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Demande d'inscription à l'application Wisikard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
</head>
<body class="bg-white md:bg-zinc-900">
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md p-4 mx-4 bg-white md:bg-white md:rounded-[30px] md:shadow-red">
        <div class="headerLogo flex justify-center items-center">
            <img src="{{ asset('images/WisiKardLogoBlack.png') }}" alt="Logo WisiKard" class="w-32 md:w-48 lg:w-96">
        </div>
        <div class="justify-center mt-10">
            <h1 class="text-center text-lg md:text-xl lg:text-2xl font-bold">Inscription Wisikard</h1>

            <!-- Afficher le message de succès -->
            @if(session('success'))
                <div class="mt-4 p-2 bg-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-10">
                <form action="{{ route('InscriptionClient') }}" method="post">
                    @csrf
                    <div class="mb-6">
                        <label for="entreprise" class="block text-sm font-medium text-gray-700">Nom de
                            l'entreprise</label>
                        <input type="text" name="entreprise" id="entreprise"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <div class="mb-6">
                        <label for="mail" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <input type="email" name="mail" id="mail"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <div class="mb-6">
                        <div class="">
                            <label for="motDePasse1" class="block text-sm font-medium text-gray-700">Mot de
                                passe</label>
                            <input type="password" name="motDePasse1" id="motDePasse1"
                                   class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   required>
                        </div>
                        <!-- Minimum 12 caractères indications -->
                        <span class="text-sm text-gray-500">Minimum 12 caractères</span>
                    </div>
                    <div class="mb-6">
                        <label for="motDePasse2" class="block text-sm font-medium text-gray-700">Validation du mot de
                            passe</label>
                        <input type="password" name="motDePasse2" id="motDePasse2"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <input id="role" name="role" class="prodId" type="hidden"
                           value="{{ isset($_GET['role']) ? htmlspecialchars($_GET['role']) : '' }}"/>
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
