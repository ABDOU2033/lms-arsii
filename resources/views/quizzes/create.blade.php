@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('teacher.courses') }}" class="text-blue-600 hover:text-blue-800">Mes Cours</a>
        > <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-800">{{ $course->title }}</a>
        > <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="text-blue-600 hover:text-blue-800">{{ $lesson->title }}</a>
        > Créer Quiz
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Créer un Quiz</h1>
            <p class="text-gray-600 mb-6">Ajoutez un nouveau quiz pour la leçon <strong>{{ $lesson->title }}</strong></p>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                    <strong>❌ Erreurs de validation:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('quiz.store', [$course, $lesson]) }}" method="POST">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-bold text-gray-800 mb-2">
                        Titre du Quiz *
                    </label>
                    <input type="text" id="title" name="title" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('title') border-red-500 @enderror"
                           value="{{ old('title') }}"
                           placeholder="Ex: Quiz sur les Bases de Données"
                           required>
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-bold text-gray-800 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Décrivez le contenu et les objectifs du quiz...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Passing Score -->
                <div class="mb-6">
                    <label for="passing_score" class="block text-sm font-bold text-gray-800 mb-2">
                        Score de Passage (%) *
                    </label>
                    <input type="number" id="passing_score" name="passing_score" 
                           min="0" max="100" step="5"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('passing_score') border-red-500 @enderror"
                           value="{{ old('passing_score', 60) }}"
                           required>
                    <p class="text-sm text-gray-600 mt-1">Les étudiants doivent obtenir au moins ce pourcentage pour réussir</p>
                    @error('passing_score')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Limit -->
                <div class="mb-6">
                    <label for="time_limit" class="block text-sm font-bold text-gray-800 mb-2">
                        Limite de Temps (minutes)
                    </label>
                    <input type="number" id="time_limit" name="time_limit" 
                           min="1" step="1"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('time_limit') border-red-500 @enderror"
                           value="{{ old('time_limit', '') }}"
                           placeholder="Laissez vide si pas de limite">
                    <p class="text-sm text-gray-600 mt-1">Optionnel - laissez vide pour aucune limite</p>
                    @error('time_limit')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
                    <p class="text-sm text-blue-800">
                        <strong>💡 Conseil:</strong> Vous pourrez ajouter des questions et réponses après avoir créé le quiz.
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition font-bold">
                        ✓ Créer le Quiz
                    </button>
                    <a href="{{ route('lessons.show', [$course, $lesson]) }}" 
                       class="flex-1 text-center bg-gray-400 text-white py-2 rounded-lg hover:bg-gray-500 transition font-bold">
                        ✕ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
