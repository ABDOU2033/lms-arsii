@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Timer et Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->title }}</h1>
        @if($quiz->time_limit)
            <div class="bg-red-50 px-4 py-2 rounded-lg border border-red-200">
                <p class="text-red-600 font-bold">
                    ⏱️ Temps restant: <span id="timer">{{ $quiz->time_limit }}:00</span>
                </p>
            </div>
        @endif
    </div>

    <!-- Progress Bar -->
    <div class="mb-6">
        <div class="flex justify-between mb-2">
            <span class="text-sm text-gray-600">Progression</span>
            <span class="text-sm text-gray-600"><span id="current-question">1</span> / {{ $questions->count() }}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div id="progress-bar" class="bg-blue-600 h-2 rounded-full" style="width: 0%;"></div>
        </div>
    </div>

    <!-- Main Form -->
    <form action="{{ route('quiz.submit', [$course, $lesson, $quiz]) }}" method="POST" id="quiz-form">
        @csrf
        <input type="hidden" name="attempt_id" value="{{ $activeAttempt->id }}">

        <div class="space-y-6">
            @forelse($questions as $index => $question)
                <div class="bg-white rounded-lg shadow-md p-6 question-card" data-question="{{ $index + 1 }}">
                    <!-- Question Number and Text -->
                    <div class="mb-4">
                        <span class="text-sm text-gray-500 font-bold">Question {{ $index + 1 }} / {{ $questions->count() }}</span>
                        <h3 class="text-lg font-bold text-gray-800 mt-1">
                            {{ $question->question_text }}
                        </h3>
                    </div>

                    <!-- Answer Options -->
                    <div class="space-y-3">
                        @forelse($question->answers as $answer)
                            <label class="flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition answer-option">
                                <input type="radio" 
                                       name="answers[{{ $question->id }}]" 
                                       value="{{ $answer->id }}"
                                       class="mt-1 w-4 h-4 text-blue-600"
                                       required>
                                <span class="ml-3 text-gray-700">{{ $answer->answer_text }}</span>
                            </label>
                        @empty
                            <p class="text-gray-500">Aucune réponse disponible</p>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg text-center">
                    <p class="text-yellow-800">⚠️ Aucune question disponible pour ce quiz</p>
                </div>
            @endforelse
        </div>

        @if($questions->count() > 0)
            <!-- Submit Button -->
            <div class="mt-8 bg-white rounded-lg shadow-md p-6 border-t-4 border-green-600">
                <div class="mb-4 p-4 bg-blue-50 rounded border border-blue-200">
                    <p class="text-sm text-blue-800">
                        ⚠️ <strong>Attention:</strong> Vérifiez vos réponses avant de soumettre. Vous ne pourrez pas les modifier après.
                    </p>
                </div>
                
                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-bold text-lg">
                    ✓ Soumettre le Quiz
                </button>
            </div>
        @endif
    </form>
</div>

<script>
    // Timer
    @if($quiz->time_limit)
    let timeLimit = {{ $quiz->time_limit }} * 60;
    const timerElement = document.getElementById('timer');
    
    const countdownInterval = setInterval(() => {
        timeLimit--;
        const minutes = Math.floor(timeLimit / 60);
        const seconds = timeLimit % 60;
        timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        
        if (timeLimit <= 0) {
            clearInterval(countdownInterval);
            document.getElementById('quiz-form').submit();
        }
    }, 1000);
    @endif

    // Update progress bar on answer selection
    document.querySelectorAll('input[type="radio"]').forEach(input => {
        input.addEventListener('change', function() {
            const totalQuestions = {{ $questions->count() }};
            const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
            const percentage = (answeredQuestions / totalQuestions) * 100;
            document.getElementById('progress-bar').style.width = percentage + '%';
        });
    });

    // Prevent accidental submission
    document.getElementById('quiz-form').addEventListener('submit', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir soumettre le quiz? Vérifiez vos réponses avant de continuer.')) {
            e.preventDefault();
        }
    });
</script>
@endsection
