@extends('layouts.app')

@section('title', 'Modifier l\'Utilisateur')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Modifier Utilisateur : {{ $user->nom }}</h5>
                    <a href="{{ route('admin.utilisateurs') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.utilisateurs.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="etudiant" {{ old('role', $user->role) == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                                <option value="enseignant" {{ old('role', $user->role) == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                                <option value="administrateur" {{ old('role', $user->role) == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            </select>
                            @if($user->id === auth()->id())
                                <small class="text-danger">Vous ne pouvez pas modifier votre propre rôle.</small>
                                <input type="hidden" name="role" value="{{ $user->role }}">
                            @endif
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Nouveau mot de passe (optionnel)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" minlength="8">
                            <small class="text-muted">Laissez vide si vous ne souhaitez pas modifier le mot de passe.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
