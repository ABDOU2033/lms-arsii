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
            <div class="card">
                <div class="card-body">
                    @if($resultats->count() > 0)
                        @foreach($resultats as $quizTitre => $reponsesParEtudiant)
                            <div class="mb-4">
                                <h5 class="mb-3">{{ $quizTitre }}</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Étudiant</th>
                                                <th>Question</th>
                                                <th>Réponse donnée</th>
                                                <th>Points obtenus</th>
                                                <th>Statut</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reponsesParEtudiant as $reponse)
                                                <tr>
                                                    <td>{{ $reponse->etudiant->user->nom }}</td>
                                                    <td>{{ Str::limit($reponse->question->enonce, 50) }}</td>
                                                    <td>{{ $reponse->contenu }}</td>
                                                    <td>{{ $reponse->score_obtenu ?? 0 }}/{{ $reponse->question->points }}</td>
                                                    <td>{{ $reponse->est_correcte ? 'Correct' : 'Incorrect' }}</td>
                                                    <td>{{ $reponse->created_at->format('d/m/Y H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
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
@endsection