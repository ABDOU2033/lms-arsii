@extends('layouts.app')

@section('title', 'Détails du Cours')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">{{ $cours->titre }}</h1>
                <div>
                    <a href="{{ route('enseignant.cours.edit', $cours) }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    <a href="{{ route('enseignant.cours.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Description</h5>
                </div>
                <div class="card-body">
                    <p>{{ $cours->description ?: 'Aucune description' }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Leçons</h5>
                    <a href="{{ route('enseignant.lecon.create', $cours) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </a>
                </div>
                <div class="card-body">
                    @if($lecons->count() > 0)
                        <div class="list-group">
                            @foreach($lecons->sortBy('ordre') as $lecon)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $lecon->titre }}</strong>
                                        <span class="badge bg-{{ $lecon->type == 'video' ? 'danger' : ($lecon->type == 'pdf' ? 'warning' : ($lecon->type == 'texte' ? 'info' : 'secondary')) }} ms-2">{{ ucfirst($lecon->type) }}</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('enseignant.lecon.edit', $lecon) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('enseignant.lecon.destroy', $lecon) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucune leçon ajoutée.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Quiz</h5>
                    <a href="{{ route('enseignant.quiz.create', $cours) }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </a>
                </div>
                <div class="card-body">
                    @if($quizzes->count() > 0)
                        <div class="list-group">
                            @foreach($quizzes as $quiz)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('enseignant.quiz.show', $quiz) }}" class="text-decoration-none">
                                            <strong>{{ $quiz->titre }}</strong>
                                        </a>
                                        <small class="text-muted d-block">Durée: {{ $quiz->duree }} min | Note max: {{ $quiz->note_max }}</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('enseignant.quiz.edit', $quiz) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('enseignant.quiz.destroy', $quiz) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucun quiz ajouté.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6>Clé d'inscription</h6>
                </div>
                <div class="card-body">
                    @if($cours->cle_inscription)
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-dark fs-5 px-3 py-2 me-2" style="font-family: monospace;">{{ $cours->cle_inscription }}</span>
                            <button onclick="navigator.clipboard.writeText('{{ $cours->cle_inscription }}'); this.innerHTML='✅ Copié!';" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mb-2">Partagez cette clé avec vos étudiants pour qu'ils puissent s'inscrire.</small>
                    @else
                        <p class="text-muted small mb-2">Aucune clé générée. Les étudiants ne peuvent pas s'inscrire sans clé.</p>
                    @endif
                    <form action="{{ route('enseignant.cours.genererCle', $cours) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $cours->cle_inscription ? 'warning' : 'primary' }}">
                            <i class="bi bi-key"></i> {{ $cours->cle_inscription ? 'Régénérer la clé' : 'Générer une clé' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6>Statistiques</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Statut:</span>
                        <span class="badge bg-{{ $cours->statut == 'publie' ? 'success' : ($cours->statut == 'brouillon' ? 'warning' : 'secondary') }}">{{ ucfirst($cours->statut) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Étudiants inscrits:</span>
                        <span>{{ $etudiants->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Leçons:</span>
                        <span>{{ $lecons->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Quiz:</span>
                        <span>{{ $quizzes->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6>Étudiants inscrits</h6>
                </div>
                <div class="card-body">
                    @if($etudiants->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($etudiants as $etudiant)
                                <li class="list-group-item px-0">{{ $etudiant->user->nom }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted small">Aucun étudiant inscrit.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection