<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"
            integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-gray-100 w-full">

<div class="flex flex-col md:flex-row">
    @include('menu.menuAdmin')

    <div class="flex-1 md:ml-24 content">

        <div class="min-h-screen p-4">

            <div class="flex flex-col md:flex-row justify-between items-center pb-4">
                <!-- Link to the add information form -->
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

            <div class="flex flex-col space-y-4">
                @foreach($messages as $message)
                    <div class="flex flex-col md:flex-row items-center justify-between p-2 bg-white rounded shadow w-full">
                        <div class="flex-grow text-center md:text-left">
                            <p id="message-{{ $message->id }}">{{ $message->message }}</p>
                        </div>
                        <div class="flex items-center mt-2 md:mt-0">
                            <button type="button" onclick="toggleEdit({{ $message->id }})"
                                    class="bg-indigo-500 text-white px-4 py-2 rounded mr-2">Modifier
                            </button>
                            <form action="{{ route('toggleMessage', $message->id) }}" method="POST"
                                  class="flex items-center">
                                @csrf
                                @method('PATCH')
                                <label class="toggle-switch">
                                    <input type="checkbox" name="afficher"
                                           {{ $message->afficher ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span class="slider"></span>
                                </label>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal Structure -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-1/2">
        <h2 class="text-xl mb-4">Modifier le message</h2>
        <form id="edit-form-modal" action="" method="POST">
            @csrf
            @method('PUT')
            <textarea name="message" id="modal-message" class="border p-2 rounded w-full mb-4" rows="4"></textarea>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('editModal')"
                        class="bg-red-500 text-white px-4 py-2 rounded mr-2">Annuler
                </button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Modal Structure -->
<div id="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-1/2">
        <h2 class="text-xl mb-4">Ajouter une Information</h2>
        <form id="add-form-modal" action="{{ route('ajoutMessage') }}" method="POST">
            @csrf
            <textarea name="message" id="add-modal-message" class="border p-2 rounded w-full mb-4" rows="4"></textarea>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('addModal')"
                        class="bg-red-500 text-white px-4 py-2 rounded mr-2">Annuler
                </button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleEdit(messageId) {
        const messageElement = document.getElementById(`message-${messageId}`);
        const modal = document.getElementById('editModal');
        const modalMessage = document.getElementById('modal-message');
        const modalForm = document.getElementById('edit-form-modal');

        modalMessage.value = messageElement.textContent.trim();
        modalForm.action = `/modifierMessage/${messageId}`;
        modal.classList.remove('hidden');
    }

    function openAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.remove('hidden');
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
    }
</script>

</body>
</html>
