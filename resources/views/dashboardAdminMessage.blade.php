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
<body class="align-items-center bg-gray-100 w-100">

<div class="flex flex-col md:flex-row">
    @include('menuAdmin')

    <div class="flex-1 md:ml-24 content">
        <div class="flex flex-col pr-4">
            @foreach($messages as $message)
                <div class="flex items-center justify-between m-2 p-2 bg-white rounded shadow w-full">
                    <div class="flex-grow">
                        <p id="message-{{ $message->id }}">{{ $message->message }}</p>
                        <form id="edit-form-{{ $message->id }}" action="{{ route('modifierMessage', $message->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="text" name="message" value="{{ $message->message }}" class="border p-2 rounded w-full">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Enregistrer</button>
                            <button type="button" onclick="cancelEdit({{ $message->id }})" class="bg-red-500 text-white px-4 py-2 rounded ml-2">Annuler</button>
                        </form>
                    </div>
                    <div class="flex items-center">
                        <button type="button" onclick="toggleEdit({{ $message->id }})" class="bg-green-500 text-white px-4 py-2 rounded mr-2">Modifier</button>
                        <form action="{{ route('toggleMessage', $message->id) }}" method="POST" class="flex items-center">
                            @csrf
                            @method('PATCH')
                            <label class="toggle-switch">
                                <input type="checkbox" name="afficher" {{ $message->afficher ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="slider"></span>
                            </label>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function toggleEdit(messageId) {
        const messageElement = document.getElementById(`message-${messageId}`);
        const editForm = document.getElementById(`edit-form-${messageId}`);
        messageElement.classList.toggle('hidden');
        editForm.classList.toggle('hidden');
    }

    function cancelEdit(messageId) {
        const messageElement = document.getElementById(`message-${messageId}`);
        const editForm = document.getElementById(`edit-form-${messageId}`);
        messageElement.classList.remove('hidden');
        editForm.classList.add('hidden');
    }
</script>

</body>
</html>