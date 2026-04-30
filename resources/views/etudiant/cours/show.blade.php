@extends('layouts.app')

@section('title', $cours->titre)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">{{ $cours->titre }}</h1>
            <small class="text-muted">{{ $cours->description }}</small>
        </div>
    </div>

    <!-- Barre de Progression -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Progression Générale</h6>
                        <span class="badge bg-primary">{{ $progression ? $progression->pourcentage : 0 }}%</span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $progression ? $progression->pourcentage : 0 }}%">
                            {{ $progression ? $progression->pourcentage : 0 }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Leçons -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-book"></i> Leçons</h5>
                </div>
                <div class="card-body">
                </div>
                <div class="list-group list-group-flush">
                    @forelse($lecons as $lecon)
                        <a href="{{ route('etudiant.lecon.show', $lecon) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $lecon->titre }}</h6>
                                    <small class="text-muted">{{ ucfirst($lecon->type) }} • Leçon {{ $lecon->ordre }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ in_array($lecon->id, $leconsVuesIds ?? []) ? 'success' : 'secondary' }}">
                                        {{ in_array($lecon->id, $leconsVuesIds ?? []) ? 'Terminée' : 'À lire' }}
                                    </span>
                                    <span class="badge bg-info ms-1">{{ ucfirst($lecon->type) }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="list-group-item text-muted">
                            Aucune leçon disponible
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quiz -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-question-circle"></i> Quiz</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($cours->quizzes as $quiz)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $quiz->titre }}</h6>
                                    <small class="text-muted d-block">
                                        ⏱ {{ $quiz->duree }} min • 📊 Note max: {{ $quiz->note_max }} pts
                                    </small>
                                    <small class="text-muted d-block">
                                        {{ $quiz->questions->count() }} questions
                                    </small>
                                </div>
                                @if($quiz->questions->count() > 0)
                                    <a href="{{ route('etudiant.quiz.show', $quiz) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-play-fill"></i> Commencer
                                    </a>
                                @else
                                    <span class="badge bg-warning text-dark">En attente de questions</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-muted">
                            Aucun quiz disponible
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    @if($cours->quizzes->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Vos Résultats sur ce Cours</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Quiz</th>
                                <th>Nombre de Questions</th>
                                <th>Vos Réponses</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cours->quizzes as $quiz)
                                @php
                                    $reponses = \App\Models\Reponse::whereHas('question', function($q) use ($quiz) {
                                        $q->where('quiz_id', $quiz->id);
                                    })->where('etudiant_id', Auth::user()->etudiant->id)->get();
                                    
                                    $score = $quiz->student_score ?? 0;
                                    $scoreMax = $quiz->note_max;
                                @endphp
                                <tr>
                                    <td>{{ $quiz->titre }}</td>
                                    <td><span class="badge bg-secondary">{{ $quiz->questions->count() }}</span></td>
                                    <td>
                                        @if($reponses->count() > 0)
                                            <span class="badge bg-info">{{ $reponses->count() }} réponses</span>
                                        @else
                                            <span class="badge bg-light text-dark">Non commencé</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($reponses->count() > 0)
                                            <strong>{{ $score }}/{{ $scoreMax }}</strong>
                                            <small class="text-muted">({{ round(($score/$scoreMax)*100, 1) }}%)</small>
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection