@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Quiz pour: {{ $lesson->title }}</h1>

    @if($lesson->quizzes->count())
        <ul class="space-y-3">
            @foreach($lesson->quizzes as $quiz)
                <li class="bg-white p-4 rounded shadow flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold">{{ $quiz->title }}</h3>
                        <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($quiz->description ?? '', 120) }}</p>
                    </div>
                    <a href="{{ route('quiz.show', [$course, $lesson, $quiz]) }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Ouvrir</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>Aucun quiz pour cette leçon.</p>
    @endif
</div>
@endsection
