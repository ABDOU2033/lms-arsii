@extends('layouts.app')

@section('title', 'Résultats des Quiz')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">Résultats des Quiz</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Mes Quiz</h5>
                </div>
                <div class="card-body">
                    @if($quizzes->count() > 0)
                        <ul class="list-group">
                            @foreach($quizzes as $quiz)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $quiz->titre }}</strong>
                                        <span class="text-muted d-block">{{ $quiz->cours->titre }}</span>
                                    </div>
                                    <span class="badge bg-primary">{{ $quiz->duree }} min</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Aucun quiz disponible.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Progression des étudiants</h5>
                </div>
                <div class="card-body">
                    @if($progressions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Cours</th>
                                        <th>Étudiant</th>
                                        <th>Progression</th>
                                        <th>Dernière maj</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($progressions as $progression)
                                        <tr>
                                            <td>{{ $progression->cours->titre }}</td>
                                            <td>{{ $progression->etudiant->user->nom }}</td>
                                            <td>{{ round($progression->pourcentage, 1) }}%</td>
                                            <td>{{ $progression->date_maj ? $progression->date_maj->format('d/m/Y H:i') : $progression->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune progression enregistrée pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill text-success me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <ul class="nav nav-tabs card-header-tabs border-bottom-0" id="resultsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold py-2 px-3 border-0 rounded-pill me-2" id="copies-tab" data-bs-toggle="tab" data-bs-target="#copies" type="button" role="tab" aria-controls="copies" aria-selected="true" style="transition: all 0.2s ease;">
                                <i class="bi bi-file-earmark-text me-1 text-primary"></i> Copies des étudiants ({{ $copies->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-2 px-3 border-0 rounded-pill" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="false" style="transition: all 0.2s ease;">
                                <i class="bi bi-list-stars me-1 text-info"></i> Réponses détaillées par question
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="resultsTabContent">
                        
                        <!-- Onglet 1: Copies des étudiants -->
                        <div class="tab-pane fade show active py-2" id="copies" role="tabpanel" aria-labelledby="copies-tab">
                            @if($copies->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr class="table-light text-secondary">
                                                <th>Étudiant</th>
                                                <th>Quiz</th>
                                                <th>Cours</th>
                                                <th>Note actuelle</th>
                                                <th>Statut</th>
                                                <th>Date soumission</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($copies as $copie)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                                                                {{ strtoupper(substr($copie->etudiant->user->nom, 0, 2)) }}
                                                            </div>
                                                            <div>
                                                                <strong class="text-dark d-block mb-0">{{ $copie->etudiant->user->nom }}</strong>
                                                                <small class="text-muted" style="font-size: 0.8rem;">{{ $copie->etudiant->user->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="fw-bold text-primary">{{ $copie->quiz->titre }}</span></td>
                                                    <td><span class="text-secondary fw-semibold">{{ $copie->quiz->cours->titre }}</span></td>
                                                    <td>
                                                        <span class="fs-5 fw-bold text-success">{{ $copie->score }}</span>
                                                        <span class="text-muted small"> / {{ $copie->quiz->note_max }} pts</span>
                                                    </td>
                                                    <td>
                                                        @if($copie->is_pending)
                                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i> À corriger</span>
                                                        @else
                                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Corrigé</span>
                                                        @endif
                                                    </td>
                                                    <td><small class="text-secondary fw-semibold">{{ $copie->date_soumission->format('d/m/Y H:i') }}</small></td>
                                                    <td class="text-end">
                                                        <a href="{{ route('enseignant.copie.corriger', ['quiz' => $copie->quiz->id, 'etudiant' => $copie->etudiant->id]) }}" 
                                                           class="btn btn-sm btn-{{ $copie->is_pending ? 'warning shadow-sm' : 'outline-primary' }} fw-bold px-3">
                                                            @if($copie->is_pending)
                                                                <i class="bi bi-pencil-square me-1"></i> Corriger la copie
                                                            @else
                                                                <i class="bi bi-eye me-1"></i> Voir la copie
                                                            @endif
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-journal-x fs-1 text-muted mb-2 d-block"></i>
                                    <h5 class="text-secondary">Aucune copie soumise</h5>
                                    <p class="text-muted small">Les copies de quiz des étudiants apparaîtront ici pour correction dès qu'ils auront validé leurs quiz.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Onglet 2: Réponses détaillées -->
                        <div class="tab-pane fade py-2" id="details" role="tabpanel" aria-labelledby="details-tab">
                            @if($resultats->count() > 0)
                                @foreach($resultats as $quizTitre => $reponsesParEtudiant)
                                    <div class="mb-5">
                                        <h5 class="mb-3 text-secondary border-bottom pb-2">{{ $quizTitre }}</h5>
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead>
                                                    <tr class="table-light">
                                                        <th>Étudiant</th>
                                                        <th>Type</th>
                                                        <th>Question</th>
                                                        <th>Réponse donnée</th>
                                                        <th>Points obtenus</th>
                                                        <th>Statut</th>
                                                        <th>Correction</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($reponsesParEtudiant as $reponse)
                                                        <tr>
                                                            <td><strong>{{ $reponse->etudiant->user->nom }}</strong></td>
                                                            <td>
                                                                <span class="badge bg-{{ $reponse->question->type === 'qcm' ? 'primary' : ($reponse->question->type === 'vrai_faux' ? 'info' : 'warning') }}">
                                                                    {{ ucfirst(str_replace('_', ' ', $reponse->question->type)) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ Str::limit($reponse->question->enonce, 40) }}</td>
                                                            <td>
                                                                @if($reponse->question->type === 'texte_libre')
                                                                    <blockquote class="blockquote mb-0 fs-6 bg-light p-2 rounded border-start border-3 border-warning" style="white-space: pre-wrap;">
                                                                        {{ $reponse->contenu }}
                                                                    </blockquote>
                                                                @else
                                                                    {{ $reponse->contenu }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="fw-bold text-{{ $reponse->est_correcte ? 'success' : 'danger' }}">
                                                                    {{ $reponse->score_obtenu ?? 0 }}
                                                                </span> / {{ $reponse->question->points }} pts
                                                            </td>
                                                            <td>
                                                                @if($reponse->est_correcte)
                                                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Correct</span>
                                                                @else
                                                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Incorrect</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($reponse->question->type === 'texte_libre')
                                                                    <form action="{{ route('enseignant.reponse.evaluer', $reponse) }}" method="POST" class="d-flex gap-1 align-items-center">
                                                                        @csrf
                                                                        <input type="number" name="score_obtenu" value="{{ $reponse->score_obtenu ?? 0 }}" min="0" max="{{ $reponse->question->points }}" class="form-control form-control-sm" style="width: 60px;" required>
                                                                        <select name="est_correcte" class="form-select form-select-sm" style="width: 100px;" required>
                                                                            <option value="1" {{ $reponse->est_correcte ? 'selected' : '' }}>Correct</option>
                                                                            <option value="0" {{ !$reponse->est_correcte ? 'selected' : '' }}>Incorrect</option>
                                                                        </select>
                                                                        <button type="submit" class="btn btn-sm btn-success text-white" title="Valider la note">
                                                                            <i class="bi bi-check-lg"></i>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <span class="text-muted small"><i class="bi bi-cpu"></i> Auto-corrigé</span>
                                                                @endif
                                                            </td>
                                                            <td><small class="text-muted">{{ $reponse->created_at->format('d/m/Y H:i') }}</small></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-graph-up fs-1 text-muted"></i>
                                    <h5 class="mt-3">Aucun résultat disponible</h5>
                                    <p class="text-muted">Les résultats des quiz apparaîtront ici une fois que les étudiants auront passé les quiz.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        background-color: transparent;
        border: none;
    }
    .nav-tabs .nav-link.active {
        color: #fff !important;
        background-color: #0d6efd !important;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25);
    }
    .nav-tabs .nav-link:hover:not(.active) {
        background-color: #f8f9fa;
        color: #343a40;
    }
</style>
@endsection