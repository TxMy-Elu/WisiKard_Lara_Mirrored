<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Connexion</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-white md:bg-zinc-100">
@include('menu.menuClient')

<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md p-4 mx-4 bg-white md:bg-white md:rounded-[30px] md:shadow-red">
        <div class="headerLogo flex justify-center items-center">
            <img src="{{ asset('images/WisiKardLogoBlack.png') }}" alt="Logo WisiKard" class="w-32 md:w-48 lg:w-96">
        </div>
        <div class="justify-center mt-10">
            <h1 class="text-center text-lg md:text-xl lg:text-2xl font-bold">Connexion Wisikard</h1>
            <div class="mt-10">
                <form action="{{ route('validationFormulaireConnexion') }}" method="post">
                    @csrf
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <input type="email" name="email" id="email"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <div class="mb-6 relative">
                        <label for="motdepasse" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input type="password" name="motdepasse" id="motdepasse"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                       <br/> <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 focus:outline-none">
                            👁️
                        </button>
                    </div>

                    @include('messageErreur')
                    @if(isset($tentativesRestantes))
                        <div class="alert alert-warning">
                            Il vous reste {{ $tentativesRestantes }} tentatives avant que votre compte ne soit
                            désactivé.
                        </div>
                    @endif

                    <div class="mt-4">
                        <div class="mb-4 text-right">
                            <a href="{{ route('motDePasseOublie') }}" class="text-red-900">Mot de passe oublié ?</a>
                        </div>

                        <div class="mb-4">
                            <button type="submit" name="boutonConnexion"
                                    class="w-full bg-red-900 text-white p-2 rounded-md">Connexion
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('motdepasse');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        // Toggle the eye icon
        this.innerHTML = type === 'password' ? '👁️' : '👁️‍🗨️';
    });
</script>
</body>
</html>
