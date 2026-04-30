@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Result Header -->
    <div class="text-center mb-8">
        @if($attempt->score >= ($quiz->passing_score ?? 60))
            <div class="mb-4">
                <span class="text-6xl">🎉</span>
            </div>
            <h1 class="text-3xl font-bold text-green-600 mb-2">Félicitations!</h1>
            <p class="text-gray-600">Vous avez réussi le quiz</p>
        @else
            <div class="mb-4">
                <span class="text-6xl">📚</span>
            </div>
            <h1 class="text-3xl font-bold text-orange-600 mb-2">Résultat</h1>
            <p class="text-gray-600">Continuez à réviser et réessayez</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Main Results -->
        <div class="lg:col-span-2">
            <!-- Score Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Votre Score</h2>
                
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <!-- Score Percentage -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white mb-2">
                            <span class="text-3xl font-bold">{{ number_format($attempt->score, 0) }}%</span>
                        </div>
                        <p class="text-sm text-gray-600">Résultat</p>
                    </div>

                    <!-- Correct Answers -->
                    <div class="text-center">
                        @php
                            $correctCount = $attemptAnswers->where('is_correct', true)->count();
                            $totalQuestions = $questions->count();
                        @endphp
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 text-green-600 mb-2">
                            <span class="text-3xl font-bold">{{ $correctCount }}/{{ $totalQuestions }}</span>
                        </div>
                        <p class="text-sm text-gray-600">Bonnes réponses</p>
                    </div>

                    <!-- Pass Status -->
                    <div class="text-center">
                        @if($attempt->score >= ($quiz->passing_score ?? 60))
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 text-green-600 mb-2">
                                <span class="text-3xl">✓</span>
                            </div>
                            <p class="text-sm text-gray-600">Réussi</p>
                        @else
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-100 text-red-600 mb-2">
                                <span class="text-3xl">✗</span>
                            </div>
                            <p class="text-sm text-gray-600">Échoué</p>
                        @endif
                    </div>
                </div>

                <!-- Score Threshold -->
                <div class="bg-gray-50 p-4 rounded border border-gray-200">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Score requis</span>
                        <span class="text-sm font-bold text-gray-800">{{ $quiz->passing_score ?? 60 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($attempt->score, 100) }}%;"></div>
                    </div>
                </div>
            </div>

            <!-- Detailed Answers -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Détail des Réponses</h2>
                
                @forelse($questions as $index => $question)
                    @php
                        $attemptAnswer = $attemptAnswers->firstWhere('question_id', $question->id);
                    @endphp
                    <div class="border-b pb-6 mb-6 last:border-b-0 last:mb-0">
                        <div class="mb-3">
                            <span class="text-sm text-gray-500 font-bold">Question {{ $index + 1 }}</span>
                            <h4 class="text-lg font-bold text-gray-800">{{ $question->question_text }}</h4>
                        </div>

                        <div class="space-y-2">
                            @foreach($question->answers as $answer)
                                @php
                                    $isSelected = $attemptAnswer && $attemptAnswer->selected_answer_id == $answer->id;
                                    $isCorrect = $answer->is_correct;
                                @endphp
                                <div class="p-3 rounded-lg border-2 
                                    @if($isSelected && $isCorrect)
                                        bg-green-50 border-green-500
                                    @elseif($isSelected && !$isCorrect)
                                        bg-red-50 border-red-500
                                    @elseif(!$isSelected && $isCorrect)
                                        bg-green-50 border-green-300
                                    @else
                                        bg-gray-50 border-gray-200
                                    @endif
                                ">
                                    <div class="flex items-start">
                                        <span class="mr-3 font-bold
                                            @if($isCorrect) text-green-600 @endif
                                            @if($isSelected && !$isCorrect) text-red-600 @endif
                                        ">
                                            @if($isCorrect)✓
                                            @elseif($isSelected && !$isCorrect)✗
                                            @else·
                                            @endif
                                        </span>
                                        <span class="
                                            @if($isSelected && !$isCorrect) text-red-700 font-bold @endif
                                            @if($isCorrect) text-green-700 @endif
                                        ">
                                            {{ $answer->answer_text }}
                                            @if($isSelected && !$isCorrect)
                                                <span class="text-red-600 text-xs ml-2">(Votre réponse)</span>
                                            @elseif($isCorrect)
                                                <span class="text-green-600 text-xs ml-2">(Bonne réponse)</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">Aucune question trouvée</p>
                @endforelse
            </div>
        </div>

        <!-- Sidebar Info -->
        <div>
            <!-- Quiz Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informations</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Leçon</p>
                        <p class="font-bold text-gray-800">{{ $lesson->title }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Quiz</p>
                        <p class="font-bold text-gray-800">{{ $quiz->title }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Date</p>
                        <p class="font-bold text-gray-800">{{ $attempt->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Durée</p>
                        <p class="font-bold text-gray-800">
                            {{ floor($attempt->created_at->diffInMinutes($attempt->finished_at)) }} min
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('lessons.show', [$course, $lesson]) }}" 
                       class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        ← Retour à la leçon
                    </a>
                    @if($attempt->score < ($quiz->passing_score ?? 60))
                        <form action="{{ route('quiz.start', [$course, $lesson, $quiz]) }}" method="POST" class="inline-block w-full">
                            @csrf
                            <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
                                🔄 Réessayer
                            </button>
                        </form>
                    @else
                        <a href="{{ route('courses.show', $course) }}" 
                           class="block w-full text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                            Voir les autres leçons
                        </a>
                    @endif
                </div>
            </div>

            <!-- Passing Score Info -->
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <h4 class="font-bold text-blue-800 mb-2">Score de passage</h4>
                <p class="text-sm text-blue-700 mb-3">
                    Vous devez obtenir au minimum <strong>{{ $quiz->passing_score ?? 60 }}%</strong> pour réussir ce quiz.
                </p>
                @if($attempt->score >= ($quiz->passing_score ?? 60))
                    <p class="text-sm text-green-700 font-bold">✓ Vous avez réussi!</p>
                @else
                    <p class="text-sm text-orange-700">Vous êtes à {{ number_format($attempt->score, 0) }}%. Réessayez!</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
