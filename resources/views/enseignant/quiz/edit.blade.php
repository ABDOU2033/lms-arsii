@extends('layouts.app')

@section('title', 'Éditer Quiz')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Éditer le Quiz</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('enseignant.quiz.update', $quiz) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                                   id="titre" name="titre" value="{{ old('titre', $quiz->titre) }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duree" class="form-label">Durée (minutes) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('duree') is-invalid @enderror" 
                                   id="duree" name="duree" value="{{ old('duree', $quiz->duree) }}" min="1" required>
                            @error('duree')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note Maximale</label>
                            <input type="number" class="form-control" value="{{ $quiz->questions->sum('points') }}" readonly disabled>
                            <small class="text-muted"><i class="bi bi-info-circle"></i> Calculée automatiquement selon la somme des points des questions</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Mettre à Jour
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
