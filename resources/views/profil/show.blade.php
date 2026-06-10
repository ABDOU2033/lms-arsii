@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')

<style>
    .profil-hero {
        background: linear-gradient(135deg, #185FA5 0%, #533AB7 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .avatar-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        flex-shrink: 0;
        border: 3px solid rgba(255,255,255,0.5);
    }
    .profil-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .profil-card .card-header {
        background: linear-gradient(90deg, #185FA5, #533AB7);
        color: white;
        border: none;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
    }
    .profil-card .card-body {
        padding: 1.5rem;
    }
    .form-label {
        font-weight: 600;
        color: #444;
        font-size: 0.875rem;
        margin-bottom: 0.4rem;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #dee2e6;
        padding: 0.6rem 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #185FA5;
        box-shadow: 0 0 0 3px rgba(24, 95, 165, 0.15);
    }
    .btn-save {
        background: linear-gradient(135deg, #185FA5, #533AB7);
        border: none;
        border-radius: 10px;
        padding: 0.6rem 2rem;
        font-weight: 600;
        color: white;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(24, 95, 165, 0.4);
        color: white;
    }
    .btn-danger-soft {
        background: #fff0f0;
        border: 1.5px solid #dc3545;
        border-radius: 10px;
        padding: 0.6rem 2rem;
        font-weight: 600;
        color: #dc3545;
        transition: all 0.2s;
    }
    .btn-danger-soft:hover {
        background: #dc3545;
        color: white;
    }
    .section-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 1.5rem 0;
        color: #888;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .section-divider::before, .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e9ecef;
    }
    .badge-role {
        font-size: 0.85rem;
        padding: 0.4em 0.9em;
        border-radius: 20px;
    }
</style>

<div class="container-fluid py-2">

    {{-- Hero Banner --}}
    <div class="profil-hero">
        <div class="avatar-circle">
            @if(auth()->user()->role === 'enseignant')
                <i class="bi bi-person-workspace"></i>
            @elseif(auth()->user()->role === 'etudiant')
                <i class="bi bi-mortarboard"></i>
            @else
                <i class="bi bi-shield-check"></i>
            @endif
        </div>
        <div>
            <h2 class="mb-1 fw-bold">{{ $user->nom }} {{ $user->prenom ?? '' }}</h2>
            <p class="mb-1 opacity-75">{{ $user->email }}</p>
            @if($user->role === 'enseignant')
                <span class="badge bg-white text-primary badge-role">
                    <i class="bi bi-person-workspace me-1"></i>Enseignant
                </span>
                @if($user->enseignant && $user->enseignant->specialite)
                    <span class="badge bg-white bg-opacity-25 ms-2 badge-role">
                        <i class="bi bi-star me-1"></i>{{ $user->enseignant->specialite }}
                    </span>
                @endif
            @elseif($user->role === 'etudiant')
                <span class="badge bg-white text-success badge-role">
                    <i class="bi bi-mortarboard me-1"></i>Étudiant
                </span>
                @if($user->etudiant && $user->etudiant->niveau)
                    <span class="badge bg-white bg-opacity-25 ms-2 badge-role">
                        <i class="bi bi-bar-chart me-1"></i>Niveau : {{ $user->etudiant->niveau }}
                    </span>
                @endif
            @else
                <span class="badge bg-white text-danger badge-role">
                    <i class="bi bi-shield-check me-1"></i>Administrateur
                </span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- Colonne principale : Formulaire de modification --}}
        <div class="col-lg-8">
            <div class="profil-card card">
                <div class="card-header">
                    <i class="bi bi-pencil-square me-2"></i>Modifier mes informations
                </div>
                <div class="card-body">
                    <form action="{{ route('profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Infos de base --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                       id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control"
                                       id="prenom" name="prenom" value="{{ old('prenom', $user->prenom ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Champs spécifiques ENSEIGNANT --}}
                        @if($user->role === 'enseignant')
                        <div class="section-divider">Informations professionnelles</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="specialite" class="form-label">Spécialité</label>
                                <input type="text" class="form-control"
                                       id="specialite" name="specialite"
                                       value="{{ old('specialite', $user->enseignant->specialite ?? '') }}"
                                       placeholder="Ex: Informatique, Mathématiques, Physique...">
                            </div>
                            <div class="col-12">
                                <label for="bio" class="form-label">Biographie</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4"
                                          placeholder="Présentez-vous en quelques lignes...">{{ old('bio', $user->enseignant->bio ?? '') }}</textarea>
                            </div>
                        </div>
                        @endif

                        {{-- Champs spécifiques ÉTUDIANT --}}
                        @if($user->role === 'etudiant')
                        <div class="section-divider">Informations académiques</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="niveau" class="form-label">Niveau d'étude</label>
                                <select class="form-select" id="niveau" name="niveau">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach(['Licence 1', 'Licence 2', 'Licence 3', 'Master 1', 'Master 2', 'Doctorat'] as $niv)
                                        <option value="{{ $niv }}" {{ old('niveau', $user->etudiant->niveau ?? '') === $niv ? 'selected' : '' }}>
                                            {{ $niv }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        {{-- Mot de passe --}}
                        <div class="section-divider">Changer le mot de passe</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="mot_de_passe" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('mot_de_passe') is-invalid @enderror"
                                       id="mot_de_passe" name="mot_de_passe"
                                       placeholder="Laisser vide pour ne pas changer">
                                @error('mot_de_passe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="mot_de_passe_confirmation" class="form-label">Confirmer</label>
                                <input type="password" class="form-control"
                                       id="mot_de_passe_confirmation" name="mot_de_passe_confirmation"
                                       placeholder="Confirmer le nouveau mot de passe">
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-save">
                                <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Colonne droite : Infos rapides + Supprimer compte --}}
        <div class="col-lg-4 d-flex flex-column gap-4">
            {{-- Récapitulatif --}}
            <div class="profil-card card">
                <div class="card-header">
                    <i class="bi bi-info-circle me-2"></i>Informations du compte
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="bi bi-calendar-check text-primary mt-1"></i>
                            <div>
                                <div class="fw-bold" style="font-size:0.8rem;color:#888;">Membre depuis</div>
                                <div>{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</div>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="bi bi-shield-lock text-success mt-1"></i>
                            <div>
                                <div class="fw-bold" style="font-size:0.8rem;color:#888;">Statut</div>
                                @if($user->statut === 'actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-warning">{{ ucfirst($user->statut) }}</span>
                                @endif
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-person-badge text-purple mt-1" style="color:#533AB7;"></i>
                            <div>
                                <div class="fw-bold" style="font-size:0.8rem;color:#888;">Rôle</div>
                                <div class="text-capitalize">{{ $user->role }}</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Zone dangereuse --}}
            <div class="profil-card card border-danger">
                <div class="card-header" style="background: linear-gradient(90deg, #dc3545, #993C1D);">
                    <i class="bi bi-exclamation-triangle me-2"></i>Zone dangereuse
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        La suppression de votre compte est définitive et entraîne la perte de toutes vos données.
                    </p>
                    <button class="btn btn-danger-soft w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-1"></i>Supprimer mon compte
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de suppression --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Cette action est <strong>irréversible</strong>. Veuillez saisir votre mot de passe pour confirmer.</p>
                <form action="{{ route('profil.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <label for="mdp_confirm" class="form-label fw-bold">Mot de passe</label>
                        <input type="password" class="form-control" id="mdp_confirm" name="mot_de_passe" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100" style="border-radius:10px;">
                        <i class="bi bi-trash me-1"></i>Oui, supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection