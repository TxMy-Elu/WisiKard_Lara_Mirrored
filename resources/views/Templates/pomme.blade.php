<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }}Wisikard</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-white text-[#14213d]" style="font-family: '{{ $carte['font'] }}';">

<!-- Section BACK -->
<div class="w-full h-60 p-4  text-[#14213d]">

    <!-- Logo -->
    @php
        // Détection des différents types de fichiers
        $logoPath = '';
        $formats = ['svg', 'png', 'jpg', 'jpeg']; // Ajouter d'autres formats si nécessaire

        // Remplacement des espaces par des underscores dans le nom d'entreprise
        $nomEntrepriseClean = str_replace(' ', '_', $carte->nomEntreprise);

        foreach ($formats as $format) {
            $path = public_path('entreprises/' . $carte->compte->idCompte . '_' . $nomEntrepriseClean . '/logos/logo.' . $format);
            if (file_exists($path)) {
                $logoPath = asset('entreprises/' . $carte->compte->idCompte . '_' . $nomEntrepriseClean . '/logos/logo.' . $format);
                break;
            }
        }
    @endphp

    @if(!empty($logoPath))
        <div class="w-full h-full max-h-24 mx-auto">
            <img src="{{ $logoPath }}" alt="Logo de l'entreprise" class="w-full h-full object-contain">
        </div>
    @endif

    <!-- Nom de l'entreprise -->
    @if($carte['nomEntreprise'] && empty($logoPath))
        <div class="mt-2">
            <h1 class="text-[#14213d] text-3xl text-center font-bold">{{ $carte['nomEntreprise'] }}</h1>
        </div>
    @endif

    <!-- Titre de l'entreprise -->
    @if($carte['titre'])
        <div>
            <h2 class="text-center text-lg font-medium">{{ $carte['titre'] }}</h2>
        </div>
    @endif

    <!-- Description de l'entreprise -->
    @if($carte['descriptif'])
        <div>
            <p class="text-center text-sm text-[#14213d]">{{ $carte['descriptif'] }}</p>
        </div>
    @endif
</div>

<!-- Section RESTE -->
<div class="bg-white text-[#e5e5e5] h-full rounded-t-xl shadow-lg">
    <div id="reste-content" class="h-full overflow-hidden">
        <div class="h-full">

            <div class="h-10 flex justify-center items-center bg-[#14213d] rounded-t-xl text-white border-b border-[#000000]">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="25" height="25" fill="currentColor">
                    <path d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z"/>
                </svg>
            </div>


        </div>
    </div>
</div>
</body>
</html>