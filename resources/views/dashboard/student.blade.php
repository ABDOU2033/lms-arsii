@extends('layouts.app')

@section('title', 'Tableau de bord - Étudiant')

@section('content')
<div class="container mx-auto py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">📊 Tableau de bord</h1>
        <p class="text-gray-600">Bienvenue, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Inscrit aux cours -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Courses Inscrites</p>
                    <p class="text-4xl font-bold text-blue-600 mt-2">{{ $enrolledCourses ?? 0 }}</p>
                </div>
                <span class="text-5xl text-blue-200">📚</span>
            </div>
        </div>

        <!-- Cours complétés -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Cours Complétés</p>
                    <p class="text-4xl font-bold text-green-600 mt-2">{{ $completedCourses ?? 0 }}</p>
                </div>
                <span class="text-5xl text-green-200">✓</span>
            </div>
        </div>

        <!-- Progression moyenne -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Progression</p>
                    <p class="text-4xl font-bold text-purple-600 mt-2">{{ round($averageProgress ?? 0) }}%</p>
                </div>
                <span class="text-5xl text-purple-200">📈</span>
            </div>
            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ round($averageProgress ?? 0) }}%;"></div>
            </div>
        </div>

        <!-- Score Quiz Moyen -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Score Moyen Quiz</p>
                    <p class="text-4xl font-bold text-orange-600 mt-2">{{ round($averageQuizScore ?? 0) }}%</p>
                </div>
                <span class="text-5xl text-orange-200">🎯</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cours Récents -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">📖 Vos Cours</h2>
                
                @if($recentCourses && $recentCourses->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentCourses as $course)
                            @php
                                $progress = $course->pivot->progress ?? 0;
                            @endphp
                            <div class="border-l-4 border-blue-500 bg-gray-50 p-4 rounded">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <a href="{{ route('courses.show', $course) }}" class="text-lg font-bold text-blue-600 hover:underline">
                                            {{ $course->title }}
                                        </a>
                                        <p class="text-sm text-gray-600">Prof: {{ $course->teacher->name ?? 'N/A' }}</p>
                                    </div>
                                    @if($progress >= 100)
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-xs font-bold">✓ Complété</span>
                                    @elseif($progress >= 75)
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-xs font-bold">🔥 Presque Fini</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded text-xs font-bold">En cours</span>
                                    @endif
                                </div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-gray-600">Progression</span>
                                    <span class="font-bold text-gray-800">{{ round($progress) }}%</span>
                                </div>
                                <div class="w-full bg-gray-300 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition" style="width: {{ $progress }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-center py-6">Vous n'êtes inscrit à aucun cours. <a href="{{ route('courses.index') }}" class="text-blue-600 hover:underline">Parcourir les cours</a></p>
                @endif
            </div>

            <!-- Quiz Récents -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">🎯 Derniers Quiz</h2>
                
                @if($quizAttempts && $quizAttempts->count() > 0)
                    <div class="space-y-3">
                        @foreach($quizAttempts as $attempt)
                            @php
                                $score = $attempt->score ?? 0;
                                $passing = $attempt->quiz->passing_score ?? 60;
                                $passed = $score >= $passing;
                            @endphp
                            <div class="border-l-4 @if($passed) border-green-500 @else border-red-500 @endif bg-gray-50 p-4 rounded">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800">{{ $attempt->quiz->title ?? 'Quiz' }}</p>
                                        <p class="text-sm text-gray-600">{{ $attempt->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold @if($passed) text-green-600 @else text-red-600 @endif">
                                            {{ round($score) }}%
                                        </p>
                                        @if($passed)
                                            <span class="text-xs text-green-600 font-bold">✓ Réussi</span>
                                        @else
                                            <span class="text-xs text-red-600 font-bold">✗ Échoué</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-center py-6">Aucun résultat de quiz. Commencez un quiz!</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Statistiques Rapides -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Statistiques</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cours en cours</span>
                        <span class="font-bold text-gray-800">
                            {{ $enrolledCourses - $completedCourses }}
                        </span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="text-gray-600">Quiz Tentés</span>
                        <span class="font-bold text-gray-800">
                            {{ $quizAttempts ? $quizAttempts->count() : 0 }}
                        </span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="text-gray-600">Score Plus Élevé</span>
                        <span class="font-bold text-green-600">
                            {{ $quizAttempts ? round($quizAttempts->max('score') ?? 0) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">⚡ Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('courses.index') }}" 
                       class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Parcourir Cours
                    </a>
                    <a href="{{ route('teacher.courses') }}" 
                       class="block w-full text-center bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                        Mes Cours
                    </a>
                    <a href="{{ route('profile.edit') }}" 
                       class="block w-full text-center bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        Mon Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
