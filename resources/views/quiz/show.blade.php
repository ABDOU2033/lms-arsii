@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $quiz->title }}</h1>

    <form method="POST" action="{{ route('quiz.submit', [$course, $lesson, $quiz]) }}">
        @csrf

        @foreach($quiz->questions as $i => $question)
            <div class="bg-white p-4 rounded shadow mb-4">
                <p class="font-medium">Q{{ $i+1 }}. {{ $question->text }}</p>
                @if($question->type === 'multiple_choice')
                    @foreach($question->answers as $answer)
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" class="mr-2">
                                <span>{{ $answer->text }}</span>
                            </label>
                        </div>
                    @endforeach
                @else
                    <textarea name="answers[{{ $question->id }}]" class="mt-2 block w-full border rounded px-3 py-2" rows="3"></textarea>
                @endif
            </div>
        @endforeach

        <div class="mt-4">
            <button class="px-4 py-2 bg-green-600 text-white rounded">Soumettre le quiz</button>
        </div>
    </form>
</div>
@endsection
