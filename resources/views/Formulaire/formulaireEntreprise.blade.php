<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wisikard - Modification informations</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <script>
        // Fonction pour vérifier avant la soumission
        function confirmEntrepriseModification(event) {
            const form = event.target;
            const nomActuel = "{{ $carte->nomEntreprise }}"; // Nom actuel de l'entreprise
            const nomEntreprisInput = document.getElementById('nomEntreprise').value;

            if (nomEntreprisInput !== nomActuel) {
                const confirmation = confirm(
                    'Attention : La modification du nom de l\'entreprise entraînera l\'invalidation de tous les anciens QR codes. Vous devrez régénérer tous les QR codes manuellement pour qu\'ils soient de nouveau utilisables. Voulez-vous continuer ?'
                );
                if (!confirmation) {
                    event.preventDefault(); // Annule la soumission si l'utilisateur refuse
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="flex flex-col md:flex-row min-h-screen">
        @include('menu.menuClient')
        <div class="flex-1 md:ml-24 p-6">
            <div class="max-w-4xl mx-auto">
                <!-- En-tête -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">
                        Modifier vos informations
                    </h1>
                    <p class="text-gray-600 mt-2">Gérez les informations de votre entreprise</p>
                </div>

                <!-- Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <div class="flex items-center">
                            
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <div class="flex items-center">
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Formulaire -->
                <form action="{{ route('updateEntreprise') }}" method="POST" 
                      class="bg-white rounded-xl shadow-lg p-8"
                      onsubmit="confirmEntrepriseModification(event)">
                    @csrf
                    @method('POST')
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label for="nomEntreprise" class="block text-gray-700 font-semibold mb-2">
                                Nom de l'entreprise
                            </label>
                            <input type="text" id="nomEntreprise" name="nomEntreprise" 
                                   value="{{ $carte->nomEntreprise }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <p class="mt-2 text-sm text-gray-600">
                                Votre lien public est : <a href="{{ url('/Kard/' . str_replace(' ', '-', $carte->nomEntreprise)) }}" class="text-blue-500 hover:underline" target="_blank">{{ url('/Kard/' . str_replace(' ', '-', $carte->nomEntreprise)) }}</a>
                            </p>
                        </div>

                        <div>
                            <label for="mail" class="block text-gray-700 font-semibold mb-2">
                                Adresse email (Connexion & Kard)
                            </label>
                            <input type="email" id="mail" name="mail" 
                                   value="{{ $carte->compte->email }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>

                        <div class="col-span-2 mt-4">
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="afficher_email" id="afficher_email" value="1" {{ $carte->afficher_email ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-blue-500 rounded focus:ring-2 focus:ring-blue-500">
                                <span class="text-gray-700 font-semibold">Afficher l'email sur la Kard</span>
                            </label>
                        </div>

                        <div>
                            <label for="tel" class="block text-gray-700 font-semibold mb-2">
                                Téléphone
                            </label>
                            <input type="tel" id="tel" name="tel" 
                                   value="{{ $carte->tel }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>

                        <div class="col-span-2">
                            <label for="adresse" class="block text-gray-700 font-semibold mb-2">
                                Adresse
                            </label>
                            <input type="text" id="adresse" name="adresse" 
                                   value="{{ $carte->ville }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                                class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">
                           Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>