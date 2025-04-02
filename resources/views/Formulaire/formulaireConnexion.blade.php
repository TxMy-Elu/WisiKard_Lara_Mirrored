<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Wisikard - Connexion</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>

<body class="bg-white md:bg-zinc-100">

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
                        <button type="button" 
                                id="togglePassword" 
                                class="absolute right-3 top-1/2 transform text-gray-500 hover:text-gray-700 focus:outline-none transition-colors duration-200"
                                aria-label="Afficher/Masquer le mot de passe">
                            <svg class="h-5 w-5" id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="h-5 w-5 hidden" id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
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
                        <div class="mb-4 flex justify-between items-center">
                            <a href="{{ route('motDePasseOublie') }}" class="text-red-900">Mot de passe oublié ?</a>
                            <a href="https://wisikard.fr/categorie-produit/offres/" class="text-red-900 hover:text-red-700">Acheter ma Wisikard</a>
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
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        
        passwordField.setAttribute('type', type);
        
        if (type === 'password') {
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
        } else {
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
        }
    });
</script>
</body>
</html>
