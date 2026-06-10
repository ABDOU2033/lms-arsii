@extends('layouts.app')

@section('title', 'Créer un Cours')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Créer un Cours</h1>
                <a href="{{ route('enseignant.cours.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('enseignant.cours.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre du cours *</label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre') }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="categorie" class="form-label">Catégorie</label>
                            <select class="form-select @error('categorie') is-invalid @enderror" id="categorie" name="categorie">
                                <option value="">-- Sélectionner --</option>
                                <option value="Informatique" {{ old('categorie') == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                                <option value="Mathématiques" {{ old('categorie') == 'Mathématiques' ? 'selected' : '' }}>Mathématiques</option>
                                <option value="Physique" {{ old('categorie') == 'Physique' ? 'selected' : '' }}>Physique</option>
                                <option value="Chimie" {{ old('categorie') == 'Chimie' ? 'selected' : '' }}>Chimie</option>
                                <option value="Langues" {{ old('categorie') == 'Langues' ? 'selected' : '' }}>Langues</option>
                                <option value="Économie" {{ old('categorie') == 'Économie' ? 'selected' : '' }}>Économie</option>
                                <option value="Gestion" {{ old('categorie') == 'Gestion' ? 'selected' : '' }}>Gestion</option>
                                <option value="Droit" {{ old('categorie') == 'Droit' ? 'selected' : '' }}>Droit</option>
                                <option value="Autre" {{ old('categorie') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('categorie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="niveau_scolaire" class="form-label">Niveau scolaire</label>
                            <select class="form-select @error('niveau_scolaire') is-invalid @enderror" id="niveau_scolaire" name="niveau_scolaire">
                                <option value="">-- Sélectionner --</option>
                                <option value="1ère année" {{ old('niveau_scolaire') == '1ère année' ? 'selected' : '' }}>1ère année</option>
                                <option value="2ème année" {{ old('niveau_scolaire') == '2ème année' ? 'selected' : '' }}>2ème année</option>
                                <option value="3ème année" {{ old('niveau_scolaire') == '3ème année' ? 'selected' : '' }}>3ème année</option>
                                <option value="Licence" {{ old('niveau_scolaire') == 'Licence' ? 'selected' : '' }}>Licence</option>
                                <option value="Master" {{ old('niveau_scolaire') == 'Master' ? 'selected' : '' }}>Master</option>
                                <option value="Doctorat" {{ old('niveau_scolaire') == 'Doctorat' ? 'selected' : '' }}>Doctorat</option>
                            </select>
                            @error('niveau_scolaire')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="annee_universitaire" class="form-label">Année universitaire</label>
                            <select class="form-select @error('annee_universitaire') is-invalid @enderror" id="annee_universitaire" name="annee_universitaire">
                                <option value="">-- Sélectionner --</option>
                                <option value="2024-2025" {{ old('annee_universitaire') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                                <option value="2025-2026" {{ old('annee_universitaire') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                                <option value="2026-2027" {{ old('annee_universitaire') == '2026-2027' ? 'selected' : '' }}>2026-2027</option>
                            </select>
                            @error('annee_universitaire')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Créer le cours
                            </button>
                            <a href="{{ route('enseignant.cours.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>Informations</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Créez un nouveau cours en remplissant les informations ci-contre. Vous pourrez ensuite ajouter des leçons et des quiz.
                    </p>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check-circle text-success"></i> Titre obligatoire</li>
                        <li><i class="bi bi-check-circle text-success"></i> Description optionnelle</li>
                        <li><i class="bi bi-check-circle text-success"></i> Catégorie, niveau et année</li>
                        <li><i class="bi bi-check-circle text-success"></i> Statut par défaut : brouillon</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection