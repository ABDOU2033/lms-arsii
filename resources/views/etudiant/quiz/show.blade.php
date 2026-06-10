@extends('layouts.app')

@section('title', $quiz->titre)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div id="quiz-timer-bar" class="d-flex justify-content-between align-items-center bg-dark bg-gradient text-white p-2 rounded shadow-sm mb-3 position-sticky" style="top: 65px; z-index: 1050;">
                <div class="fw-bold">
                    <i class="bi bi-clipboard-check"></i>
                    Quiz en cours : {{ $quiz->titre }}
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-fill me-2"></i>
                    <span id="timer-text" class="fs-5">{{ $quiz->duree }}:00</span>
                </div>
                <div class="progress flex-grow-1 ms-3" style="height: 8px; min-width: 220px; max-width: 280px;">
                    <div id="timer-progress" class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-3 mb-md-0">{{ $quiz->titre }}</h1>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <h5><i class="bi bi-exclamation-triangle"></i> Erreurs de soumission :</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if(!$quiz->questions || $quiz->questions->count() === 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Ce quiz n'est pas encore prêt.</strong>
                    <br>Il n'y a pas encore de questions disponibles.
                </div>
                <a href="{{ route('etudiant.cours.show', $quiz->cours_id) }}" class="btn btn-secondary mt-2">
                    <i class="bi bi-arrow-left"></i> Retour au cours
                </a>
            @else
                <form method="POST" action="{{ route('etudiant.quiz.submit', $quiz) }}" id="quiz-form" onsubmit="return validateQuiz()">
                    @csrf
                    <div class="row">
                        @foreach($quiz->questions as $index => $question)
                            <div class="col-12 mb-4">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">Question {{ $index + 1 }} / {{ $quiz->questions->count() }} 
                                            <span class="badge bg-primary ms-2">{{ $question->points }} pts</span>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>{{ $question->enonce }}</strong></p>

                                        @if($question->type === 'qcm')
                                            @if($question->choixReponses->count() === 0)
                                                <div class="alert alert-danger">Aucun choix disponible pour cette question</div>
                                            @else
                                                <p class="text-muted small mb-2"><i class="bi bi-info-circle"></i> Cochez la ou les bonne(s) réponse(s) <strong>(max 2)</strong></p>
                                                <div class="list-group qcm-group" data-question-id="{{ $question->id }}">
                                                    @foreach($question->choixReponses as $choix)
                                                        <label class="list-group-item list-group-item-action">
                                                            <input class="form-check-input me-2 qcm-checkbox" type="checkbox"
                                                                   name="reponses[{{ $question->id }}][]"
                                                                   value="{{ $choix->contenu }}">
                                                            {{ $choix->contenu }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @elseif($question->type === 'vrai_faux')
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check" name="reponses[{{ $question->id }}]" id="q{{ $question->id }}v" value="Vrai" required>
                                                <label class="btn btn-outline-success" for="q{{ $question->id }}v">✓ Vrai</label>

                                                <input type="radio" class="btn-check" name="reponses[{{ $question->id }}]" id="q{{ $question->id }}f" value="Faux" required>
                                                <label class="btn btn-outline-danger" for="q{{ $question->id }}f">✗ Faux</label>
                                            </div>
                                        @elseif($question->type === 'texte_libre')
                                            <textarea class="form-control" name="reponses[{{ $question->id }}]" rows="4" placeholder="Entrez votre réponse..." required></textarea>
                                        @else
                                            <div class="alert alert-warning">Type de question non reconnu: {{ $question->type }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-check-circle"></i> Soumettre le quiz
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
// Limiter les QCM à 2 réponses maximum
document.addEventListener('DOMContentLoaded', function() {
    const qcmGroups = document.querySelectorAll('.qcm-group');
    qcmGroups.forEach(function(group) {
        const checkboxes = group.querySelectorAll('.qcm-checkbox');
        checkboxes.forEach(function(cb) {
            cb.addEventListener('change', function() {
                const checked = group.querySelectorAll('.qcm-checkbox:checked');
                if (checked.length > 2) {
                    this.checked = false;
                    alert('Vous ne pouvez pas sélectionner plus de 2 réponses.');
                }
            });
        });
    });
});

function validateQuiz() {
    // Vérifier les QCM (checkboxes groupées par question)
    const qcmGroups = document.querySelectorAll('.qcm-group');
    for (const group of qcmGroups) {
        const checked = group.querySelectorAll('.qcm-checkbox:checked');
        if (checked.length === 0) {
            alert('Veuillez cocher au moins une réponse pour chaque question à choix multiple.');
            group.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }
        if (checked.length > 2) {
            alert('Vous ne pouvez pas sélectionner plus de 2 réponses pour une question QCM.');
            group.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }
    }

    // Vérifier les autres types (vrai/faux et texte libre)
    const questions = document.querySelectorAll('[name^="reponses["]:not(.qcm-checkbox)');
    const questionGroups = {};
    
    // Grouper les réponses par question
    questions.forEach(input => {
        const name = input.name;
        const questionId = name.match(/reponses\[(\d+)\]/)[1];
        if (!questionGroups[questionId]) {
            questionGroups[questionId] = [];
        }
        questionGroups[questionId].push(input);
    });
    
    // Vérifier que chaque question a une réponse
    for (const [questionId, inputs] of Object.entries(questionGroups)) {
        const hasAnswer = inputs.some(input => input.checked || (input.type === 'textarea' && input.value.trim()));
        if (!hasAnswer) {
            alert('Veuillez répondre à toutes les questions avant de soumettre.');
            return false;
        }
    }
    
    return confirm('Êtes-vous sûr de vouloir soumettre ce quiz ? Vous ne pourrez plus modifier vos réponses.');
}

window.addEventListener('DOMContentLoaded', () => {
    let timeLeft = {{ $quiz->duree }} * 60;
    const timerText = document.getElementById('timer-text');
    const timerElement = document.getElementById('timer');
    const timerProgress = document.getElementById('timer-progress');
    const totalTime = {{ $quiz->duree }} * 60;

    const updateTimer = () => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerText.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

        const percentage = totalTime > 0 ? Math.max(0, (timeLeft / totalTime) * 100) : 0;
        timerProgress.style.width = percentage + '%';

        if (percentage <= 16.67 && percentage > 8.33) {
            timerElement.className = 'badge bg-warning text-dark fs-6 px-3 py-2 shadow mb-2';
            timerProgress.className = 'progress-bar bg-warning';
        } else if (percentage <= 8.33 && percentage > 0) {
            timerElement.className = 'badge bg-danger text-white fs-6 px-3 py-2 shadow mb-2';
            timerProgress.className = 'progress-bar bg-danger';
        } else if (percentage <= 0) {
            timerElement.className = 'badge bg-dark text-white fs-6 px-3 py-2 shadow mb-2';
            timerProgress.className = 'progress-bar bg-dark';
            timerText.textContent = 'TEMPS ÉCOULÉ';
            timerProgress.style.width = '0%';
        } else {
            timerElement.className = 'badge bg-success text-white fs-6 px-3 py-2 shadow mb-2';
            timerProgress.className = 'progress-bar bg-success';
        }

        if (timeLeft === 300) {
            console.warn('5 minutes restantes');
        }
        if (timeLeft === 60) {
            console.warn('1 minute restante');
        }
    };

    const countdown = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerText.textContent = 'TEMPS ÉCOULÉ';
            timerProgress.style.width = '0%';
            document.getElementById('quiz-form').submit();
            return;
        }

        timeLeft--;
        updateTimer();
    }, 1000);

    updateTimer();
});
</script>
@endsection