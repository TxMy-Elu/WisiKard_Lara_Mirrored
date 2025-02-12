<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Description - {{ $titre }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold text-center mb-6">{{ $titre }}</h1>

    <div class="bg-white p-4 rounded-lg shadow-md space-y-4">
        @foreach($txts as $txt)
            <p class="text-gray-800">{{ $txt->txt }}</p>
        @endforeach
    </div>

    <div class="text-center mt-6">
        <a href="{{ route('dashboardClientAide') }}" class="text-blue-500 hover:text-blue-700">← Retour à l'Aide</a>
    </div>
</body>
</html>
