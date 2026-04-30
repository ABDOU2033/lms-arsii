@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Fil d'Ariane -->
        <div class="mb-6 flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('courses.index') }}" class="hover:text-blue-600">Cours</a>
            <span>›</span>
            <a href="{{ route('courses.show', $course) }}" class="hover:text-blue-600">{{ $course->title }}</a>
            <span>›</span>
            <span class="text-gray-900 font-medium">{{ $lesson->title }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Contenu Principal -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-md p-8 mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $lesson->title }}</h1>
                    
                    <div class="prose prose-sm max-w-none text-gray-700 mb-8 py-6 border-y">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-6">
                        @if($lesson->quiz)
                            <a href="{{ route('quiz.show', [$course, $lesson, $lesson->quiz]) }}" 
                               class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                Faire le Quiz
                            </a>
                        @endif
                        
                        <a href="{{ route('courses.show', $course) }}" 
                           class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                            Retour au Cours
                        </a>
                    </div>
                </div>
            </div>

            <!-- Barre Latérale -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $course->title }}</h3>
                    
                    <div class="space-y-4 text-sm mb-6">
                        <div>
                            <p class="text-gray-600">Enseignant</p>
                            <p class="font-medium text-gray-900">{{ $course->teacher->name }}</p>
                        </div>

                        <div>
                            <p class="text-gray-600">Niveau</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($course->level) }}</p>
                        </div>
                    </div>

                    <hr class="my-6">

                    <div>
                        <p class="text-gray-900 font-bold mb-3 text-sm">LEÇONS</p>
                        <ul class="space-y-1">
                            @foreach($course->lessons->sortBy('order') as $l)
                                <li>
                                    <a href="{{ route('lessons.show', [$course, $l]) }}"
                                       class="px-3 py-2 rounded block text-sm transition {{ $l->id === $lesson->id ? 'bg-blue-100 text-blue-600 font-bold' : 'text-gray-700 hover:bg-gray-100' }}">
                                        {{ $l->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
