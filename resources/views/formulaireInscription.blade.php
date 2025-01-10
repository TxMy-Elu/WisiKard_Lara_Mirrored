<!-- formulaireConnexion.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Inscription</title>

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
            <h1 class="text-center text-lg md:text-xl lg:text-2xl font-bold">Inscription Wisikard</h1>
            <div class="mt-10">
                <form action="{{ route('validationFormulaireInscription') }}" method="post">
                    @csrf
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <input type="email" name="email" id="email"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <div class="mb-6">
                        <div class="">
                            <label for="motdepasse" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            <input type="password" name="motDePasse1" id="motdepasse"
                                   class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   required>
                        </div>
                        <!-- Minimum 13 charactères indications-->
                        <span class="text-sm text-gray-500">Minimum 12 charactères</span>
                    </div>
                    <div class="mb-6">
                        <label for="motdepasse" class="block text-sm font-medium text-gray-700">Validation du mot de
                            passe</label>
                        <input type="password" name="motDePasse2" id="motdepasse"
                               class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                    </div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>

                    <!-- selected role -->
                    <div class="mb-4">
                        <select name="role" id="role"
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            @foreach($roles as $role)
                                <option value="{{ $role->role }}">{{ $role->role }}</option>
                            @endforeach
                        </select>
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