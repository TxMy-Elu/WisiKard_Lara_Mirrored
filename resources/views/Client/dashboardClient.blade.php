<!-- resources/views/client/dashboardClient.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col md:flex-row">
    @include('menu.menuClient')
    <div class="flex-1 md:ml-24 p-4">
        @if($messageContent != "Aucun message disponible" || empty($messageContent))
            <div class="bg-zinc-400 bg-opacity-45 border border-zinc-400 text-zin-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Information :</strong>
                <span class="block sm:inline">{{ $messageContent }}</span>
            </div>
        @endif


       <iframe src="{{ route('Templates.iframes.pomme') }}" width="100%" height="500px"></iframe>
        <iframe src="{{ route('Templates.iframes.peche') }}" width="100%" height="500px"></iframe>
        <iframe src="{{ route('Templates.iframes.fraise') }}" width="100%" height="500px"></iframe>

    </div>
</div>

</body>
</html>
