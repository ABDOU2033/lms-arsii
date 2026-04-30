@extends('layouts.app')

@section('title', 'Créer une Leçon')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Créer une Leçon - {{ $cours->titre }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('enseignant.lecon.store', $cours) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                                   id="titre" name="titre" value="{{ old('titre') }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contenu" class="form-label">Contenu <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('contenu') is-invalid @enderror" 
                                      id="contenu" name="contenu" rows="8" required>{{ old('contenu') }}</textarea>
                            <div class="form-text">
                                Pour une leçon réelle :
                                <ul class="mb-0">
                                    <li>Texte : écrivez directement le contenu.</li>
                                    <li>Vidéo : collez un lien YouTube ou le chemin d’un fichier MP4.</li>
                                </ul>
                            </div>
                            @error('contenu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="texte" {{ old('type') === 'texte' ? 'selected' : '' }}>Texte</option>
                                <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Vidéo</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ordre" class="form-label">Ordre <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('ordre') is-invalid @enderror" 
                                   id="ordre" name="ordre" value="{{ old('ordre', 1) }}" min="1" required>
                            @error('ordre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Créer
                            </button>
                            <a href="{{ route('enseignant.cours.show', $cours) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
