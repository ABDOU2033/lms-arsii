@extends('layouts.app')

@section('title', 'Résultats - ' . $quiz->titre)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if($reponses->contains('score_obtenu', -1))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> 
                    <strong>Félicitations ! Quiz soumis avec succès.</strong> Certaines questions nécessitent une correction manuelle. Votre note définitive sera disponible prochainement.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @else
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> 
                    <strong>Quiz soumis !</strong> Vous pouvez voir vos résultats ci-dessous et retenter le quiz si nécessaire.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                    <h4 class="card-title">{{ $quiz->titre }}</h4>
                    <p class="text-muted mb-3">{{ $quiz->cours->titre }}</p>
                    
                    @if($reponses->contains('score_obtenu', -1))
                        @php
                            $reponsesAuto = $reponses->where('score_obtenu', '!=', -1);
                            $scoreAuto = $reponsesAuto->sum('score_obtenu');
                            $reponsesEnAttente = $reponses->where('score_obtenu', -1)->count();
                            $totalReponses = $reponses->count();
                        @endphp
                        <div class="my-4">
                            <div class="display-4 font-weight-bold text-success">
                                {{ $scoreAuto }} <span class="fs-2 text-muted">pts obtenus</span>
                            </div>
                            <p class="text-muted mb-2">
                                <i class="bi bi-check-circle-fill text-success me-1"></i>
                                <strong>{{ $reponsesAuto->count() }}</strong> question(s) corrigée(s) automatiquement
                            </p>
                            <p class="text-muted mb-3">
                                <i class="bi bi-hourglass-split text-info me-1"></i>
                                <strong>{{ $reponsesEnAttente }}</strong> question(s) en attente de correction manuelle
                            </p>
                            <div class="progress mb-3" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" 
                                     style="width: {{ ($reponsesAuto->count() / $totalReponses) * 100 }}%" 
                                     aria-valuenow="{{ ($reponsesAuto->count() / $totalReponses) * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ round(($reponsesAuto->count() / $totalReponses) * 100) }}%
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-1"></i> 
                            <strong>Note partielle :</strong> Votre note définitive sera mise à jour dès que votre enseignant aura corrigé toutes les questions à réponse libre.
                        </div>
                    @else
                        @php
                            $correctesCount = $reponses->where('est_correcte', true)->count();
                            $totalReponses = $reponses->count();
                            $pourcentageCorrect = $totalReponses > 0 ? ($correctesCount / $totalReponses) * 100 : 0;
                            $noteMax = $quiz->note_max;
                            $scorePourAffichage = min($scoreTotal, $noteMax);
                            $pourcentageScore = $noteMax > 0 ? ($scorePourAffichage / $noteMax) * 100 : 0;
                            $pourcentageArrondi = round($pourcentageScore, 1);
                        @endphp
                        <div class="my-4">
                            <div class="display-4 font-weight-bold">
                                <span class="text-{{ $pourcentageCorrect >= 75 ? 'success' : ($pourcentageCorrect >= 50 ? 'warning' : 'danger') }}">
                                    {{ $scorePourAffichage }}/{{ $noteMax }}
                                </span>
                            </div>
                            <p class="text-muted">
                                Pourcentage : <strong>{{ $pourcentageArrondi }}%</strong>
                            </p>
                        </div>

                        @if($pourcentageArrondi >= 100)
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-check-circle"></i> Excellent ! Score parfait (100%).
                            </div>
                        @elseif($pourcentageScore >= 75)
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-award"></i> Très bien ! Bon travail.
                            </div>
                        @elseif($pourcentageScore >= 50)
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-circle"></i> Bon effort. Vous pouvez faire mieux.
                            </div>
                        @else
                            <div class="alert alert-danger mb-0">
                                <i class="bi bi-x-circle"></i> À améliorer. Réessayez pour progresser.
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Statistiques</h5>
                    
                    @if($reponses->contains('score_obtenu', -1))
                        <div class="mb-3">
                            <p class="mb-1 text-muted"><strong>Questions auto-corrigées :</strong></p>
                            @php
                                $autoCorrigees = $reponses->where('score_obtenu', '!=', -1);
                                $autoCorrectes = $autoCorrigees->where('est_correcte', true)->count();
                                $totalAuto = $autoCorrigees->count();
                                $pourcentageAuto = $totalAuto > 0 ? ($autoCorrectes / $totalAuto) * 100 : 0;
                            @endphp
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar bg-info text-white" role="progressbar" style="width: {{ $pourcentageAuto }}%">
                                    {{ $autoCorrectes }} / {{ $totalAuto }} correctes
                                </div>
                            </div>
                            <small class="text-muted"><i class="bi bi-cpu"></i> Les QCM et Vrai/Faux sont déjà vérifiés.</small>
                        </div>
                    @else
                        @php
                            $correctesCount = $reponses->where('est_correcte', true)->count();
                            $totalReponses = $reponses->count();
                        @endphp
                        <div class="mb-3">
                            <p class="mb-1"><strong>Réponses correctes :</strong></p>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" style="width: {{ $totalReponses > 0 ? ($correctesCount / $totalReponses) * 100 : 0 }}%">
                                    {{ $correctesCount }}/{{ $totalReponses }}
                                </div>
                            </div>
                        </div>
                    @endif

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
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }} {{ $reponse->score_obtenu === -1 ? 'bg-light-warning text-dark' : ($reponse->est_correcte ? 'bg-light-success' : 'bg-light-danger') }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                        <span class="me-2">
                                            @if($reponse->score_obtenu === -1)
                                                <i class="bi bi-hourglass-split text-warning"></i>
                                            @elseif($reponse->est_correcte)
                                                <i class="bi bi-check-circle text-success"></i>
                                            @else
                                                <i class="bi bi-x-circle text-danger"></i>
                                            @endif
                                        </span>
                                        <strong>Question {{ $index + 1 }} :</strong> 
                                        {{ Str::limit($reponse->question->enonce, 40) }}
                                        <span class="ms-auto text-end d-flex gap-1">
                                            @if($reponse->score_obtenu === -1)
                                                <span class="badge bg-info"><i class="bi bi-hourglass-split"></i> Correction manuelle en cours</span>
                                            @elseif($reponse->question->type === 'vrai_faux')
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
                                            @php
                                                // Pour QCM : séparer les réponses cochées par l'étudiant
                                                $selectedAnswers = array_filter(array_map('trim', explode('|', $reponse->contenu)));
                                            @endphp
                                            <div class="mb-3">
                                                <p><strong>Options disponibles :</strong></p>
                                                <ul class="list-group">
                                                    @foreach($reponse->question->choixReponses as $choix)
                                                        @php
                                                            $isSelected = in_array(trim($choix->contenu), $selectedAnswers);
                                                        @endphp
                                                        <li class="list-group-item {{ $choix->est_correcte ? 'bg-success bg-opacity-10 border-success' : '' }} {{ $isSelected && !$choix->est_correcte ? 'bg-danger bg-opacity-10 border-danger' : '' }}">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span>
                                                                    {{ $choix->contenu }}
                                                                    @if($choix->est_correcte)
                                                                        <span class="badge bg-success ms-2">✓ Correcte</span>
                                                                    @endif
                                                                    @if($isSelected && !$choix->est_correcte)
                                                                        <span class="badge bg-danger ms-2">✗ Votre choix (faux)</span>
                                                                    @elseif($isSelected && $choix->est_correcte)
                                                                        <span class="badge bg-success ms-2">✓ Votre choix</span>
                                                                    @endif
                                                                </span>
                                                                @if($isSelected)
                                                                    <i class="bi bi-check2-square {{ $choix->est_correcte ? 'text-success' : 'text-danger' }} fs-5"></i>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <div class="mb-3">
                                                <p><strong>Votre réponse :</strong></p>
                                                <div class="alert alert-light border-start border-4 border-info">
                                                    <div class="d-flex align-items-start">
                                                        <i class="bi bi-chat-left-text-fill text-info fs-4 me-3"></i>
                                                        <div class="flex-grow-1" style="white-space: pre-wrap;">{{ $reponse->contenu }}</div>
                                                    </div>
                                                </div>
                                                @if($reponse->score_obtenu === -1)
                                                    <div class="alert alert-info bg-opacity-25 border-info">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-hourglass-split fs-5 text-info me-2"></i>
                                                            <div>
                                                                <strong>En attente de correction manuelle</strong><br>
                                                                <small class="text-muted">Votre enseignant évaluera cette réponse prochainement. Votre note définitive sera alors mise à jour.</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="alert alert-{{ $reponse->est_correcte ? 'success' : ($reponse->score_obtenu > 0 ? 'warning' : 'danger') }} bg-opacity-25 border-{{ $reponse->est_correcte ? 'success' : ($reponse->score_obtenu > 0 ? 'warning' : 'danger') }}">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-{{ $reponse->est_correcte ? 'check-circle-fill' : ($reponse->score_obtenu > 0 ? 'info-circle-fill' : 'x-circle-fill') }} fs-5 text-{{ $reponse->est_correcte ? 'success' : ($reponse->score_obtenu > 0 ? 'warning' : 'danger') }} me-2"></i>
                                                            <div>
                                                                <strong>
                                                                    @if($reponse->est_correcte)
                                                                        Réponse validée
                                                                    @elseif($reponse->score_obtenu > 0)
                                                                        Réponse partiellement correcte
                                                                    @else
                                                                        Réponse incorrecte
                                                                    @endif
                                                                </strong><br>
                                                                <small class="text-muted">
                                                                    Score : <strong>{{ $reponse->score_obtenu }}/{{ $reponse->question->points }} pts</strong>
                                                                    @if(!$reponse->est_correcte && $reponse->score_obtenu > 0)
                                                                        - Votre enseignant peut ajuster cette note si nécessaire.
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            <p>
                                                <strong>Résultat :</strong>
                                                @if($reponse->score_obtenu === -1)
                                                    <span class="badge bg-info"><i class="bi bi-hourglass-split"></i> Note en attente de correction manuelle</span>
                                                @elseif($reponse->est_correcte)
                                                    <span class="badge bg-success">✓ Correct</span>
                                                @else
                                                    <span class="badge bg-danger">✗ Incorrect</span>
                                                @endif
                                            </p>
                                            <p>
                                                <strong>Points obtenus :</strong> 
                                                @if($reponse->score_obtenu === -1)
                                                    <span class="badge bg-secondary">-- / {{ $reponse->question->points }} points (En attente)</span>
                                                @elseif($reponse->question->type === 'vrai_faux')
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
    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.15) !important;
    }
</style>
@endsection
