{{-- Affichage des messages d'erreurs --}}

@if (isset($messagesErreur))
    @if (count($messagesErreur) > 0)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-3 text-left" role="alert">
            <b class="block font-bold">Erreur :</b>
            <ul class="list-disc pl-5">
                @foreach ($messagesErreur as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif