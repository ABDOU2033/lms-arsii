@extends('layouts.auth')

@section('title', 'Connexion — LMS ARSII')

@section('content')
    <!-- Header -->
    <div class="login-card-header">
        <div class="login-card-icon">
            <i class="bi bi-person-lock"></i>
        </div>
        <h2 class="login-title">Bienvenue !</h2>
        <p class="login-subtitle">Connectez-vous à votre espace d'apprentissage</p>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-custom alert-danger-custom">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label-custom" for="email">Adresse Email</label>
            <div class="input-wrapper">
                <i class="bi bi-envelope input-icon"></i>
                <input
                    type="email"
                    class="form-input"
                    id="email"
                    name="email"
                    placeholder="exemple@email.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>
        </div>

        <div class="form-group">
            <label class="form-label-custom" for="mot_de_passe">Mot de passe</label>
            <div class="input-wrapper">
                <i class="bi bi-lock input-icon"></i>
                <input
                    type="password"
                    class="form-input"
                    id="mot_de_passe"
                    name="mot_de_passe"
                    placeholder="••••••••"
                    required
                >
            </div>
        </div>

        <button type="submit" class="btn-login" id="btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>
            Se connecter
        </button>
    </form>

    <div class="divider"></div>

    <div class="register-link">
        Pas encore de compte ?
        <a href="{{ route('register') }}">S'inscrire gratuitement</a>
    </div>
@endsection
