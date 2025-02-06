<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <!-- Inclure les bibliothèques Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"
            integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
</head>
<body class="bg-gray-100 w-full">

<div class="flex flex-col md:flex-row">
    @include('menu.menuAdmin') <!-- Inclure le menu admin -->

    <div class="flex-1 md:ml-24">
        <div class="min-h-screen p-4">
            <div class="flex flex-col md:flex-row justify-between items-center pb-4">
                <!-- Lien vers le formulaire d'ajout d'information -->
                <div class="flex items-center w-full md:w-auto">
                    <button type="button" onclick="openAddModal()"
                            class="w-full md:w-auto px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter une Information
                    </button>
                </div>
            </div>

            <!-- Liste des messages -->
            <div class="flex flex-col space-y-4">
                @foreach($messages as $message)
                    <div class="flex flex-col md:flex-row items-center justify-between p-4 bg-white rounded-lg shadow-md w-full">
                        <div class="flex-grow text-center md:text-left">
                            <p id="message-{{ $message->id }}">{{ $message->message }}</p>
                        </div>
                        <div class="flex items-center mt-2 md:mt-0">
                            <!-- Bouton pour supprimer le message -->
                            <form action="{{ route('SupprimerMessage', $message->id) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg mr-2">Supprimer</button>
                            </form>
                            <!-- Bouton pour modifier le message -->
                            <button type="button" onclick="toggleEdit({{ $message->id }})"
                                    class="bg-indigo-500 text-white px-4 py-2 rounded-lg mr-2">Modifier
                            </button>
                            <!-- Bouton pour basculer l'affichage du message -->
                            <form action="{{ route('toggleMessage', $message->id) }}" method="POST" class="flex items-center">
                                @csrf
                                @method('PATCH')
                                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                    <input type="checkbox" name="afficher"
                                           class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 border-gray-300 appearance-none cursor-pointer checked:right-0 checked:border-indigo-500"
                                           {{ $message->afficher ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal pour modifier un message -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-1/2">
        <h2 class="text-xl mb-4">Modifier le message</h2>
        <form id="edit-form-modal" action="" method="POST">
            @csrf
            @method('PUT')
            <textarea name="message" id="modal-message" class="border border-gray-300 p-2 rounded-lg w-full mb-4" rows="4"></textarea>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('editModal')"
                        class="bg-red-900 text-white px-4 py-2 rounded-lg mr-2">Annuler
                </button>
                <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded-lg">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour ajouter un message -->
<div id="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-1/2">
        <h2 class="text-xl mb-4">Ajouter une Information</h2>
        <form id="add-form-modal" action="{{ route('ajoutMessage') }}" method="POST">
            @csrf
            <textarea name="message" id="add-modal-message" class="border border-gray-300 p-2 rounded-lg w-full mb-4" rows="4"></textarea>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('addModal')"
                        class="bg-red-900 text-white px-4 py-2 rounded-lg mr-2">Annuler
                </button>
                <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded-lg">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fonction pour ouvrir le modal de modification
    function toggleEdit(messageId) {
        const messageElement = document.getElementById(`message-${messageId}`);
        const modal = document.getElementById('editModal');
        const modalMessage = document.getElementById('modal-message');
        const modalForm = document.getElementById('edit-form-modal');

        modalMessage.value = messageElement.textContent.trim();
        modalForm.action = `/modifierMessage/${messageId}`;
        modal.classList.remove('hidden');
    }

    // Fonction pour ouvrir le modal d'ajout
    function openAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.remove('hidden');
    }

    // Fonction pour fermer un modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
    }
</script>

</body>
</html>
