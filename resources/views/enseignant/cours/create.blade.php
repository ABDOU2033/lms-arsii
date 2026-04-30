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
                        <li><i class="bi bi-check-circle text-success"></i> Statut par défaut : brouillon</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection