<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Confirmation de l'inscription</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
</head>
<body class="flex flex-col items-center min-h-screen bg-gray-100">

<main class="w-full max-w-3xl px-4 mt-8">
    <form method="POST" action="{{ route('validationChangementMotDePasse') }}" class="bg-white shadow-md rounded-lg p-6 mx-auto">
        @csrf
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                Confirmation de l'inscription
            </h1>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-left" role="alert">
                <b>Inscription effectuée avec succès !</b>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-700 mb-3">AVANT DE CONTINUER : configurez la double authentifcation !</h2>
                <div class="flex items-start space-x-6">
                    <div>
                        <img src="data:image/png;base64, {{ $qrCode }}" alt="QR Code" class="shadow-md rounded">
                    </div>
                    <div class="flex-1 text-sm text-gray-600">
                        <p>
                            À l'aide d'une application d'authentifcation (A2F) comme
                            <a href="https://authy.com/download/" class="text-blue-500 hover:underline">Authy</a>
                            ou <strong>Google Authenticator</strong>
                            (<a href="https://apps.apple.com/app/google-authenticator/id388497605" class="text-blue-500 hover:underline">iOS</a> ou
                            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" class="text-blue-500 hover:underline">Android</a>),
                            scannez le QR-Code ci-contre afin de vous connecter à votre compte.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('connexion') }}" class="text-blue-500 hover:underline">
                    Se connecter
                </a>
            </div>
        </div>
    </form>
</main>
</body>
</html>