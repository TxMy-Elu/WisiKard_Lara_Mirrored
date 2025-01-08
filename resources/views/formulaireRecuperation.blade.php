<!-- formulaireConnexion.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mot de passe oublié</title>

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
<body class="bg-white md:bg-zinc-900">
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md p-4 mx-4 bg-white md:bg-white md:rounded-[30px] md:shadow-red">
        <div class="headerLogo flex justify-center items-center">
            <img src="{{ asset('images/WisiKardLogoBlack.png') }}" alt="Logo WisiKard" class="w-32 md:w-48 lg:w-96">
        </div>
        <div class="justify-center mt-10">
            <h1 class="text-center text-lg md:text-xl lg:text-2xl font-bold">Mot de passe oublié</h1>
            <div class="mt-10">
                <form method="POST" action="{{ route('validationEmailMotDePasseOublie') }}">
                    @csrf
                    <div class="mb-6">
                        <label for="motdepasse" class="block text-sm font-medium text-gray-700">Entrez l'adresse email
                            associée à votre compte :</label>
                        <input type="email" name="email" id="email"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <div class="mb-6">

                        @include('messageErreur')

                        <div class="mb-4">
                            <button type="submit" name="boutonRecuperer"
                                    class="w-full bg-red-900 text-white p-2 rounded-md">Valider
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