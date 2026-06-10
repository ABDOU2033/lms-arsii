@extends('layouts.app')

@section('title', 'Correction de copie - ' . $quiz->titre)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('enseignant.resultats') }}">Résultats</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Correction de copie</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3"><i class="bi bi-journal-check me-2 text-primary"></i> Correction de copie</h1>
                <a href="{{ route('enseignant.resultats') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour aux résultats
                </a>
            </div>
        </div>
    </div>

    <!-- Infos Copie -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1 text-primary">{{ $quiz->titre }}</h4>
                            <p class="text-muted mb-0">
                                Cours : <strong>{{ $quiz->cours->titre }}</strong> | 
                                Étudiant : <strong class="text-dark">{{ $etudiant->user->nom }}</strong> ({{ $etudiant->user->email }})
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <span class="fs-5 text-muted me-2">Score total actuel :</span>
                            <span class="fs-3 fw-bold text-success">{{ $quiz->calculerScore($etudiant) }}</span>
                            <span class="fs-4 text-muted"> / {{ $quiz->note_max }} pts</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de Correction globale -->
    <form action="{{ route('enseignant.copie.enregistrer', ['quiz' => $quiz->id, 'etudiant' => $etudiant->id]) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-9">
                @foreach($reponses as $index => $reponse)
                    <div class="card mb-4 border-0 shadow-sm rounded-3">
                        <div class="card-header d-flex justify-content-between align-items-center border-0 py-3 {{ $reponse->score_obtenu === -1 ? 'bg-warning bg-opacity-10 text-warning-emphasis' : ($reponse->est_correcte ? 'bg-success bg-opacity-10 text-success-emphasis' : 'bg-danger bg-opacity-10 text-danger-emphasis') }}">
                            <h5 class="card-title mb-0">
                                <span class="badge bg-secondary me-2">Question {{ $index + 1 }}</span>
                                {{ Str::limit($reponse->question->enonce, 60) }}
                            </h5>
                            <span class="badge bg-{{ $reponse->question->type === 'qcm' ? 'primary' : ($reponse->question->type === 'vrai_faux' ? 'info' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $reponse->question->type)) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <!-- Question énonce -->
                            <div class="mb-3">
                                <p class="text-dark fw-semibold fs-5 mb-2">{{ $reponse->question->enonce }}</p>
                                <small class="text-muted"><i class="bi bi-award-fill text-warning me-1"></i> Valeur de la question : <strong>{{ $reponse->question->points }} pts</strong></small>
                            </div>

                            <hr>

                            <!-- Réponse de l'étudiant -->
                            <div class="mb-4">
                                <p class="mb-2 text-muted fw-semibold">Réponse donnée par l'étudiant :</p>
                                @if($reponse->question->type === 'texte_libre')
                                    <div class="p-3 bg-light rounded-3 border-start border-4 border-warning mb-2" style="white-space: pre-wrap; font-size: 1.05rem;">
                                        {{ $reponse->contenu }}
                                    </div>
                                @else
                                    <div class="p-3 bg-light rounded-3 mb-2 fw-semibold text-dark">
                                        {{ $reponse->contenu }}
                                    </div>
                                @endif
                            </div>

                            <!-- Options de correction -->
                            @if($reponse->question->type === 'texte_libre')
                                <div class="bg-light p-3 rounded-3 border">
                                    <h6 class="text-primary mb-3"><i class="bi bi-sliders me-1"></i> Évaluation de la réponse libre</h6>
                                    
                                    @if($reponse->question->reponse_attendue)
                                        <div class="mb-3 p-3 bg-white rounded border-start border-3 border-info">
                                            <p class="mb-1 fw-semibold text-info"><i class="bi bi-lightbulb me-1"></i> Réponse attendue (guide):</p>
                                            <div class="text-muted" style="white-space: pre-wrap;">{{ $reponse->question->reponse_attendue }}</div>
                                        </div>
                                        
                                        @if($reponse->score_obtenu !== -1)
                                            @php
                                                $studentNorm = strtolower(trim($reponse->contenu));
                                                $expectedNorm = strtolower(trim($reponse->question->reponse_attendue));
                                                $similarity = 0;
                                                similar_text($studentNorm, $expectedNorm, $similarity);
                                                $similarityPercent = round($similarity, 1);
                                            @endphp
                                            <div class="alert alert-{{ $similarityPercent >= 90 ? 'success' : ($similarityPercent >= 80 ? 'info' : 'warning') }} mb-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-{{ $similarityPercent >= 90 ? 'check-circle-fill' : ($similarityPercent >= 80 ? 'info-circle-fill' : 'exclamation-triangle-fill') }} fs-4 me-2"></i>
                                                    <div>
                                                        <strong>Auto-correction suggérée :</strong><br>
                                                        <small>
                                                            Similarité : <strong>{{ $similarityPercent }}%</strong> 
                                                            @if($similarityPercent >= 90)
                                                                - Réponse considérée comme <strong class="text-success">correcte</strong>
                                                            @elseif($similarityPercent >= 80)
                                                                - Réponse <strong class="text-info">partiellement correcte</strong>
                                                            @else
                                                                - Réponse <strong class="text-warning">incorrecte</strong>
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold text-dark mb-1">Score attribué :</label>
                                            <div class="input-group">
                                                <input type="number" name="reponses[{{ $reponse->id }}][score_obtenu]" 
                                                       value="{{ $reponse->score_obtenu === -1 ? 0 : $reponse->score_obtenu }}" 
                                                       min="0" max="{{ $reponse->question->points }}" 
                                                       class="form-control form-control-lg fw-bold text-center text-success" 
                                                       required>
                                                <span class="input-group-text fw-semibold">/ {{ $reponse->question->points }} pts</span>
                                            </div>
                                            @if($reponse->score_obtenu !== -1 && $reponse->question->reponse_attendue)
                                                <small class="text-muted mt-1 d-block">
                                                    <i class="bi bi-cpu-fill text-info"></i> Score auto-proposé
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label fw-semibold text-dark mb-1">Statut d'évaluation :</label>
                                            <select name="reponses[{{ $reponse->id }}][est_correcte]" class="form-select form-select-lg fw-semibold" required>
                                                <option value="1" {{ $reponse->est_correcte ? 'selected' : '' }}>✓ Réponse validée comme correcte</option>
                                                <option value="0" {{ !$reponse->est_correcte ? 'selected' : '' }}>✗ Réponse considérée incorrecte</option>
                                            </select>
                                            @if($reponse->score_obtenu !== -1 && $reponse->question->reponse_attendue)
                                                <small class="text-muted mt-1 d-block">
                                                    <i class="bi bi-info-circle"></i> Vous pouvez ajuster si nécessaire
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-md-3 text-end pt-3">
                                            @if($reponse->score_obtenu === -1)
                                                <span class="badge bg-warning text-dark p-2 fs-6"><i class="bi bi-clock-history"></i> En attente</span>
                                            @else
                                                <span class="badge bg-{{ $reponse->est_correcte ? 'success' : 'info' }} p-2 fs-6">
                                                    <i class="bi bi-{{ $reponse->est_correcte ? 'check-circle-fill' : 'info-circle-fill' }}"></i> 
                                                    {{ $reponse->est_correcte ? 'Validé' : 'Auto-évalué' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Auto-corrigé -->
                                <div class="bg-light p-3 rounded-3 border border-dashed d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-success-emphasis fw-semibold d-block mb-1">
                                            <i class="bi bi-cpu-fill text-success me-1"></i> Auto-correction automatique active
                                        </span>
                                        @if($reponse->question->type === 'qcm' || $reponse->question->type === 'vrai_faux')
                                            <small class="text-muted">Bonne(s) réponse(s) configurée(s) : 
                                                <strong>
                                                    {{ $reponse->question->choixReponses->where('est_correcte', true)->pluck('contenu')->join(', ') }}
                                                </strong>
                                            </small>
                                        @endif
                                    </div>
                                    <div>
                                        <input type="hidden" name="reponses[{{ $reponse->id }}][score_obtenu]" value="{{ $reponse->score_obtenu }}">
                                        <input type="hidden" name="reponses[{{ $reponse->id }}][est_correcte]" value="{{ $reponse->est_correcte ? 1 : 0 }}">
                                        <span class="fs-5 fw-bold text-{{ $reponse->est_correcte ? 'success' : 'danger' }}">
                                            {{ $reponse->score_obtenu }} / {{ $reponse->question->points }} pts
                                        </span>
                                        <span class="badge bg-{{ $reponse->est_correcte ? 'success' : 'danger' }} ms-2">
                                            {{ $reponse->est_correcte ? 'Correct' : 'Incorrect' }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sidebar Actions de validation -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-shield-check text-success display-3 mb-3"></i>
                        <h5 class="fw-bold mb-3">Terminer la correction</h5>
                        <p class="text-muted small mb-4">
                            Veuillez revoir toutes les réponses libres avant d'enregistrer la correction globale. Cela mettra à jour la note finale de l'étudiant et actualisera sa progression dans le cours.
                        </p>
                        <button type="submit" class="btn btn-success btn-lg w-100 mb-3 shadow">
                            <i class="bi bi-save me-1"></i> Valider la correction
                        </button>
                        <a href="{{ route('enseignant.resultats') }}" class="btn btn-light w-100">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
