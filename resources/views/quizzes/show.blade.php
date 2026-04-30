@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('courses.index') }}" class="text-blue-600 hover:text-blue-800">Cours</a>
        > <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-800">{{ $course->title }}</a>
        > <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="text-blue-600 hover:text-blue-800">{{ $lesson->title }}</a>
        > {{ $quiz->title }}
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Quiz Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $quiz->title }}</h1>
                @if($quiz->description)
                    <p class="text-gray-600 mb-6">{{ $quiz->description }}</p>
                @endif

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
                    <p class="text-blue-800">
                        <strong>{{ $questions->count() }} question(s)</strong> - Score de passage: <strong>{{ $quiz->passing_score ?? 60 }}%</strong>
                    </p>
                </div>

                @if(auth()->user()->enrolledIn($course->id) || $lesson->is_free)
                    <form action="{{ route('quiz.start', [$course, $lesson, $quiz]) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-bold">
                            📝 Répondre au Quiz
                        </button>
                    </form>
                @else
                    <div class="bg-red-50 border border-red-200 p-4 rounded">
                        <p class="text-red-600">
                            <strong>❌ Accès refusé</strong><br>
                            Vous devez vous inscrire au cours pour répondre au quiz.
                        </p>
                        <a href="{{ route('courses.show', $course) }}" class="text-red-600 underline mt-2 inline-block">
                            S'inscrire au cours →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Quiz Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informations</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Questions</p>
                        <p class="text-xl font-bold text-blue-600">{{ $questions->count() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Score minimum requis</p>
                        <p class="text-xl font-bold text-green-600">{{ $quiz->passing_score ?? 60 }}%</p>
                    </div>
                    @if($quiz->time_limit)
                    <div>
                        <p class="text-gray-600">Limite de temps</p>
                        <p class="text-xl font-bold text-orange-600">{{ $quiz->time_limit }} minutes</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Aperçu des Questions -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-3">Aperçu des questions:</h4>
                <div class="space-y-2">
                    @forelse($questions as $index => $question)
                        <div class="text-sm text-gray-600">
                            <p><strong>Q{{ $index + 1 }}:</strong> {{ Str::limit($question->question_text, 40) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-600">Aucune question disponible</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
