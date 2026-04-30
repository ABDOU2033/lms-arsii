@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- En-tête du cours -->
    <div class="bg-white shadow-md py-12 mb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Image -->
                <div class="md:col-span-1">
                    @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}" 
                             alt="{{ $course->title }}"
                             class="w-full h-64 object-cover rounded-lg shadow-lg">
                    @else
                        <div class="w-full h-64 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg shadow-lg flex items-center justify-center">
                            <svg class="w-24 h-24 text-white opacity-40" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Infos du cours -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-4 py-1 bg-blue-600 text-white text-sm font-bold rounded-full">
                            {{ ucfirst($course->level) }}
                        </span>
                        <span class="text-gray-600">Catégorie: {{ $course->category ?? 'Non spécifiée' }}</span>
                    </div>

                    <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ $course->title }}</h1>
                    
                    <p class="text-gray-700 text-lg mb-6">{{ $course->description }}</p>

                    <!-- Enseignant -->
                    <div class="flex items-center gap-2 mb-6 pb-6 border-b">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700">Enseignant: <strong>{{ $course->teacher->name }}</strong></span>
                    </div>

                    <!-- Boutons d'action -->
                    @auth
                        @php
                            $enrolled = auth()->user()->studentCourses()->where('course_id', $course->id)->exists();
                        @endphp

                        <div class="flex gap-3">
                            @if($enrolled)
                                <form method="POST" action="{{ route('courses.unenroll', $course) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold text-lg">
                                        Se Désinscrire
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('courses.enroll', $course) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold text-lg">
                                        S'Inscrire
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-lg">
                            Se Connecter pour S'inscrire
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu -->
    <div class="container mx-auto px-4">
        <!-- Messages flash -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg border border-red-400">
                {{ session('error') }}
            </div>
        @endif

        <!-- Leçons -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-900">Leçons</h2>
                @auth
                    @if(auth()->id() === $course->teacher_id)
                        <a href="{{ route('teacher.lessons.create', $course) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            + Ajouter une Leçon
                        </a>
                    @endif
                @endauth
            </div>
            
            @if($course->lessons->count())
                <div class="space-y-4">
                    @foreach($course->lessons as $lesson)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <a href="{{ route('lessons.show', [$course, $lesson]) }}" 
                                       class="text-xl font-bold text-blue-600 hover:text-blue-700">
                                        Leçon {{ $loop->iteration }}: {{ $lesson->title }}
                                    </a>
                                    <p class="text-gray-600 mt-2">
                                        {{ Str::limit($lesson->content, 150) }}
                                    </p>
                                </div>

                                <div class="flex flex-col gap-2 items-end">
                                    @if($lesson->quiz)
                                        <div class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                            Quiz: {{ $lesson->quiz->title }}
                                        </div>
                                    @endif
                                    
                                    @auth
                                        @if(auth()->id() === $course->teacher_id)
                                            <div class="flex gap-2">
                                                <a href="{{ route('teacher.lessons.edit', [$course, $lesson]) }}" 
                                                   class="px-3 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-600 transition">
                                                    Modifier
                                                </a>
                                                <form method="POST" action="{{ route('teacher.lessons.destroy', [$course, $lesson]) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <p class="text-gray-600">Aucune leçon disponible pour ce cours.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
