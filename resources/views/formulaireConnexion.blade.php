<!-- formulaireConnexion.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Connexion</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .shadow-red {
            box-shadow: 5px 5px 5px rgba(255, 0, 0, 0.5);
        }
    </style>
</head>
<body class="bg-zinc-900">
<div class="flex justify-center items-center h-screen">
    <div class="bg-white w-1/4 h-4/6 p-4 rounded-[30px] shadow-red">
        <div class="headerLogo flex justify-center items-center">
            <img src="{{ asset('images/WisiKardLogoBlack.png') }}" alt="Exemple d'image" class="w-96">
        </div>
        <div class="justify-center mt-10">
            <h1 class="text-center text-2xl font-bold">Connexion Wisikard</h1>
            <div class="mt-10">
                <form action="{{ route('validationFormulaireConnexion') }}" method="post">
                    <div class="mt-16">
                        @csrf
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                            <input type="email" name="email" id="email"
                                   class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   required>
                        </div>
                        <div class="mb-6">
                            <label for="motdepasse" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            <input type="password" name="motdepasse" id="motdepasse"
                                   class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   required>
                        </div>
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
                                    class="w-full bg-red-900 text-white p-2 rounded-md ">Connexion
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