<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }}Wisikard</title>

    <script src="https://cdn.lordicon.com/lordicon.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&family=Oswald:wght@400;700&family=Ubuntu:wght@400;700&family=Playfair+Display:wght@400;700&family=Work+Sans:wght@400;700&family=Bona+Nova:wght@400;700&family=Exo+2:wght@400;700&family=Pacifico&family=Gruppo&family=Rokkitt:wght@400;700&display=swap"
          rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<!-- Conteneur de la page -->
<body class="h-screen w-screen bg-zinc-800" style="font-family: '{{ $carte['font'] }}';">
<!-- Présentation entreprise -->
<div class="relative">
    <!-- Forme décorative -->
    <div class="w-full h-42 aspect-square bg-red-800 [clip-path:polygon(0_0,100%_0,100%_100%,0_calc(100%-70px))] flex flex-col justify-center items-center space-y-2 text-center">

    </div>

</div>
</body>
</html>