{{-- Menu principal situé en haut de toutes les pages --}}

@php
    $isAuth = isset($_COOKIE["auth"]);
    $isNotAuth = !(isset($_COOKIE["auth"]));
    $connexion = session("connexion");
    $isAdmin = (session()->has("isAdmin"));
    var_dump($isAdmin);
    $isNotConnected = !(session()->has("connexion")) && !(isset($_COOKIE["auth"]));
@endphp

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('accueil') }}">Authentification</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a @class([
                    'nav-link',
                    'disabled' => $isAuth,
                ]) href="{{ route('connexion') }}">Se connecter</a>
                </li>
                <li class="nav-item">
                    <a @class([
                    'nav-link',
                    'disabled' => $isNotConnected,
                ]) href="{{ route('deconnexion') }}">Déconnexion</a>
                </li>
                <li class="nav-item">
                    <a @class([
                    'nav-link',
                    'disabled' => $isNotAuth,
                ]) href="{{ route('profil') }}">Profil</a>
                </li>
                <li class="nav-item">
                    <a @class([
                'nav-link',
                'disabled' => $isNotAuth || !$isAdmin,
            ]) href="{{ route('inscription') }}">Inscription</a>
                </li>
            </ul>
        </div>
    </div>
</nav>