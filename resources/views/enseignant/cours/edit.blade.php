@extends('layouts.app')

@section('title', 'Modifier le Cours')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Modifier le Cours</h1>
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
                    <form action="{{ route('enseignant.cours.update', $cours) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre du cours *</label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $cours->titre) }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $cours->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut *</label>
                            <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                                <option value="brouillon" {{ old('statut', $cours->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                <option value="publie" {{ old('statut', $cours->statut) == 'publie' ? 'selected' : '' }}>Publié</option>
                                <option value="archive" {{ old('statut', $cours->statut) == 'archive' ? 'selected' : '' }}>Archivé</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Mettre à jour
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
                    <h6>Informations du cours</h6>
                </div>
                <div class="card-body">
                    <p><strong>Créé le :</strong> {{ $cours->created_at->format('d/m/Y') }}</p>
                    <p><strong>Étudiants inscrits :</strong> {{ $cours->etudiants->count() }}</p>
                    <p><strong>Leçons :</strong> {{ $cours->lecons->count() }}</p>
                    <p><strong>Quiz :</strong> {{ $cours->quizzes->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection