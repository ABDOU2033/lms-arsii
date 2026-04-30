{{-- Quick Test Page --}}
@extends('layouts.app')

@section('title', 'Debug Quiz')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>🔍 Debug Information</h4>
        </div>
        <div class="card-body">
            <p><strong>Quiz ID:</strong> {{ $quiz->id ?? 'N/A' }}</p>
            <p><strong>Quiz Titre:</strong> {{ $quiz->titre ?? 'N/A' }}</p>
            <p><strong>Questions Count:</strong> 
                @if(isset($quiz))
                    {{ $quiz->questions()->count() }}
                @else
                    Quiz not loaded
                @endif
            </p>
            
            <hr>
            
            <h5>All Quizzes in DB:</h5>
            <pre>
            @forelse(\App\Models\Quiz::all() as $q)
- {{ $q->id }}: {{ $q->titre }} (Questions: {{ $q->questions()->count() }})
            @empty
No quizzes found
            @endforelse
            </pre>
        </div>
    </div>
</div>
@endsection
