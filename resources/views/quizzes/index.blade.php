@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('courses.index') }}" class="text-blue-600 hover:text-blue-800">Cours</a>
        > <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-800">{{ $course->title }}</a>
        > <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="text-blue-600 hover:text-blue-800">{{ $lesson->title }}</a>
        > Quiz
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Quiz Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $quiz->title }}</h1>
                <p class="text-gray-600 mb-4">{{ $quiz->description }}</p>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-blue-50 p-3 rounded">
                        <p class="text-sm text-gray-600">Score de passage</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $quiz->passing_score ?? 60 }}%</p>
                    </div>
                    @if($quiz->time_limit)
                    <div class="bg-orange-50 p-3 rounded">
                        <p class="text-sm text-gray-600">Limite de temps</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $quiz->time_limit }} min</p>
                    </div>
                    @endif
                </div>

                @if(auth()->user()->enrolledIn($course->id) || $lesson->is_free)
                    <form action="{{ route('quiz.start', [$course, $lesson, $quiz]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                            Commencer le Quiz
                        </button>
                    </form>
                @else
                    <p class="text-red-600">Vous devez vous inscrire au cours pour répondre au quiz.</p>
                @endif
            </div>

            <!-- Number of Questions -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <p class="text-blue-800">
                    <strong>{{ $quiz->questions()->count() }} questions</strong> à répondre
                </p>
            </div>
        </div>

        <!-- Sidebar - Tentatives -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Vos Tentatives</h3>
                
                @if($attempts->isEmpty())
                    <p class="text-gray-600 text-center py-4">Aucune tentative</p>
                @else
                    <div class="space-y-3">
                        @foreach($attempts as $attempt)
                        <div class="border-l-4 border-green-500 bg-gray-50 p-3 rounded">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-sm text-gray-600">
                                        {{ $attempt->created_at->format('d/m/Y H:i') }}
                                    </p>
                                    @if($attempt->finished_at)
                                        <p class="text-lg font-bold">
                                            <span class="text-green-600">{{ number_format($attempt->score, 2) }}%</span>
                                        </p>
                                    @else
                                        <p class="text-yellow-600 text-sm">En cours...</p>
                                    @endif
                                </div>
                                @if($attempt->score >= ($quiz->passing_score ?? 60))
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">
                                        ✓ Réussi
                                    </span>
                                @elseif($attempt->finished_at)
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold">
                                        ✗ Échoué
                                    </span>
                                @endif
                            </div>
                            @if($attempt->finished_at)
                            <a href="{{ route('quiz.result', [$course, $lesson, $quiz, $attempt]) }}" 
                               class="text-blue-600 text-sm hover:underline">
                                Voir les résultats →
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Informations -->
            <div class="bg-gray-50 rounded-lg p-4 mt-4 border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-2">Conseils:</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✓ Lisez bien chaque question</li>
                    <li>✓ Vérifiez vos réponses avant de soumettre</li>
                    <li>✓ Vous pouvez faire plusieurs tentatives</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
