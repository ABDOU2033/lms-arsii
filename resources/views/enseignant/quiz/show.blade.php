@extends('layouts.app')

@section('title', 'Détails du Quiz')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">{{ $quiz->titre }}</h1>
                <a href="{{ route('enseignant.cours.show', $quiz->cours) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour au cours
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Questions</h5>
                    <a href="{{ route('enseignant.question.create', $quiz) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </a>
                </div>
                <div class="card-body">
                    @if($questions->count() > 0)
                        <div class="accordion" id="questionsAccordion">
                            @foreach($questions as $index => $question)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                            <strong>{{ $question->enonce }}</strong>
                                            <span class="badge bg-{{ $question->type == 'qcm' ? 'primary' : ($question->type == 'vrai_faux' ? 'success' : 'info') }} ms-2">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</span>
                                            <small class="text-muted ms-2">{{ $question->points }} pts</small>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#questionsAccordion">
                                        <div class="accordion-body">
                                            @if($question->type === 'qcm' || $question->type === 'vrai_faux')
                                                <h6>Choix de réponse :</h6>
                                                <ul class="list-group mb-3">
                                                    @foreach($question->choixReponses as $choix)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $choix->contenu }}
                                                            @if($choix->est_correcte)
                                                                <span class="badge bg-success">Correct</span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @elseif($question->type === 'texte_libre')
                                                @if($question->reponse_attendue)
                                                    <div class="mb-3 p-3 bg-info bg-opacity-10 rounded border-start border-3 border-info">
                                                        <p class="mb-1 fw-semibold text-info"><i class="bi bi-lightbulb me-1"></i> Réponse attendue (guide de correction):</p>
                                                        <div class="text-muted" style="white-space: pre-wrap;">{{ $question->reponse_attendue }}</div>
                                                    </div>
                                                @else
                                                    <p class="mb-3 text-muted"><i class="bi bi-info-circle me-1"></i> Réponse libre attendue (aucun guide de correction fourni).</p>
                                                @endif
                                            @endif
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('enseignant.question.edit', $question) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i> Éditer
                                                </a>
                                                <form action="{{ route('enseignant.question.destroy', $question) }}" method="POST" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr?')">
                                                        <i class="bi bi-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucune question ajoutée.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6>Informations du quiz</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Durée:</span>
                        <span>{{ $quiz->duree }} min</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Note maximale:</span>
                        <span>{{ $quiz->note_max }} pts</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Questions:</span>
                        <span>{{ $questions->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total points:</span>
                        <span>{{ $questions->sum('points') }} pts</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6>Résultats récents</h6>
                </div>
                <div class="card-body">
                    @if($resultats->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($resultats->take(5) as $etudiant => $reponses)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span>{{ $etudiant }}</span>
                                    <small class="text-muted">{{ $reponses->count() }} réponses</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small">Aucun résultat disponible.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection