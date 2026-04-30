@extends('layouts.app')

@section('title', 'Résultats - ' . $quiz->titre)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> 
                <strong>Quiz soumis!</strong> Vous pouvez réessayer autant de fois que vous le souhaitez.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">✅ Quiz Terminé</h1>
                <div>
                    <a href="{{ route('etudiant.quiz.show', $quiz->id) }}" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise"></i> Réessayer
                    </a>
                    <a href="{{ route('etudiant.cours.show', $quiz->cours_id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Retour au cours
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé Score -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ $quiz->titre }}</h4>
                    <p class="text-muted">{{ $quiz->cours->titre }}</p>
                    
                    @php
                        $correctesCount = $reponses->where('est_correcte', true)->count();
                        $totalReponses = $reponses->count();
                        $pourcentageCorrect = $totalReponses > 0 ? ($correctesCount / $totalReponses) * 100 : 0;
                    @endphp

                    <div class="my-4">
                        <div class="display-4 font-weight-bold">
                            <span class="text-{{ $pourcentageCorrect >= 75 ? 'success' : ($pourcentageCorrect >= 50 ? 'warning' : 'danger') }}">
                                @php
                                    $noteMax = $quiz->note_max;
                                    $scorePourAffichage = min($scoreTotal, $noteMax);
                                @endphp
                                {{ $scorePourAffichage }}/{{ $noteMax }}
                            </span>
                        </div>
                        <p class="text-muted">
                            Pourcentage : <strong>{{ $noteMax > 0 ? round(($scorePourAffichage / $noteMax) * 100, 1) : 0 }}%</strong>
                        </p>
                    </div>

                    @php
                        $pourcentageScore = $noteMax > 0 ? ($scorePourAffichage / $noteMax) * 100 : 0;
                        $pourcentageArrondi = round($pourcentageScore, 1);
                    @endphp

                    @if($pourcentageArrondi >= 100)
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Excellent ! Score parfait (100%).
                        </div>
                    @elseif($pourcentageScore >= 75)
                        <div class="alert alert-info">
                            <i class="bi bi-award"></i> Très bien ! Bon travail, mais vous pouvez faire encore mieux pour atteindre 100%.
                        </div>
                    @elseif($pourcentageScore >= 50)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-circle"></i> Bon effort. Vous pouvez faire mieux.
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle"></i> À améliorer. Réessayez pour progresser.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques</h5>
                    
                    <div class="mb-3">
                        <p class="mb-1"><strong>Réponses correctes :</strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $totalReponses > 0 ? ($correctesCount / $totalReponses) * 100 : 0 }}%">
                                {{ $correctesCount }}/{{ $totalReponses }}
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1"><strong>Durée du quiz :</strong> {{ $quiz->duree }} minutes</p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1"><strong>Nombre de questions :</strong> {{ $questions->count() }}</p>
                    </div>

                    <div>
                        <p class="mb-1"><strong>Date de soumission :</strong> {{ $reponses->first()->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails des Réponses -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Détails de vos réponses</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionReponses">
                        @forelse($reponses as $index => $reponse)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }} {{ $reponse->est_correcte ? 'bg-light-success' : 'bg-light-danger' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                        <span class="me-2">
                                            @if($reponse->est_correcte)
                                                <i class="bi bi-check-circle text-success"></i>
                                            @else
                                                <i class="bi bi-x-circle text-danger"></i>
                                            @endif
                                        </span>
                                        <strong>Question {{ $index + 1 }} :</strong> 
                                        {{ Str::limit($reponse->question->enonce, 40) }}
                                        <span class="ms-auto text-end">
                                            @if($reponse->question->type === 'vrai_faux')
                                                @if($reponse->est_correcte)
                                                    <span class="badge bg-success">{{ $reponse->contenu }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $reponse->contenu }}</span>
                                                @endif
                                            @else
                                                <span class="badge {{ $reponse->est_correcte ? 'bg-success' : 'bg-danger' }}">{{ $reponse->score_obtenu }}/{{ $reponse->question->points }}</span>
                                            @endif
                                        </span>
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#accordionReponses">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <p><strong>Question :</strong></p>
                                            <p>{{ $reponse->question->enonce }}</p>
                                        </div>

                                        <div class="mb-3">
                                            <p><strong>Type :</strong> 
                                                <span class="badge bg-{{ $reponse->question->type === 'qcm' ? 'primary' : ($reponse->question->type === 'vrai_faux' ? 'info' : 'warning') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $reponse->question->type)) }}
                                                </span>
                                            </p>
                                        </div>

                                        @if($reponse->question->type === 'qcm' || $reponse->question->type === 'vrai_faux')
                                            <div class="mb-3">
                                                <p><strong>Options disponibles :</strong></p>
                                                <ul class="list-group">
                                                    @foreach($reponse->question->choixReponses as $choix)
                                                        <li class="list-group-item {{ $choix->est_correcte ? 'bg-success bg-opacity-10 border-success' : '' }} {{ $reponse->contenu === $choix->contenu ? 'border-2' : '' }}">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span>
                                                                    {{ $choix->contenu }}
                                                                    @if($choix->est_correcte)
                                                                        <span class="badge bg-success ms-2">Réponse correcte</span>
                                                                    @endif
                                                                    @if($reponse->contenu === $choix->contenu && !$choix->est_correcte)
                                                                        <span class="badge bg-danger ms-2">Votre réponse</span>
                                                                    @elseif($reponse->contenu === $choix->contenu)
                                                                        <span class="badge bg-success ms-2">Votre réponse</span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <div class="mb-3">
                                                <p><strong>Votre réponse :</strong></p>
                                                <div class="alert alert-info">
                                                    {{ $reponse->contenu }}
                                                </div>
                                                <p class="text-muted small">
                                                    <i class="bi bi-info-circle"></i> 
                                                    Cette réponse nécessite une correction manuelle par l'enseignant.
                                                </p>
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            <p>
                                                <strong>Résultat :</strong>
                                                @if($reponse->est_correcte)
                                                    <span class="badge bg-success">✓ Correct</span>
                                                @else
                                                    <span class="badge bg-danger">✗ Incorrect</span>
                                                @endif
                                            </p>
                                            <p>
                                                <strong>Points obtenus :</strong> 
                                                @if($reponse->question->type === 'vrai_faux')
                                                    @if($reponse->est_correcte)
                                                        <span class="badge bg-success">{{ $reponse->score_obtenu }}/{{ $reponse->question->points }} réussi</span>
                                                    @else
                                                        <span class="badge bg-danger">Vous avez répondu {{ $reponse->contenu }} ({{ $reponse->score_obtenu }}/{{ $reponse->question->points }})</span>
                                                    @endif
                                                @else
                                                    <span class="badge {{ $reponse->est_correcte ? 'bg-success' : 'bg-danger' }}">{{ $reponse->score_obtenu }}/{{ $reponse->question->points }} points</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-warning">
                                Aucune réponse trouvée.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('etudiant.cours.show', $quiz->cours_id) }}" class="btn btn-primary">
                    <i class="bi bi-house"></i> Retour au cours
                </a>
                <a href="{{ route('etudiant.resultats') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-graph-up"></i> Voir tous les résultats
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-success {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    .bg-light-danger {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
</style>
@endsection
