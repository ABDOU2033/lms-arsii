@extends('layouts.app')

@section('title', 'Tableau de bord - Enseignant')

@section('content')
<div class="container mx-auto py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">👨‍🏫 Tableau de bord Enseignant</h1>
        <p class="text-gray-600">Gérez vos cours et suivez vos étudiants</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Vos cours -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Vos Cours</p>
                    <p class="text-4xl font-bold text-blue-600 mt-2">{{ $courses ?? 0 }}</p>
                </div>
                <span class="text-5xl text-blue-200">📚</span>
            </div>
        </div>

        <!-- Étudiants Total -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Étudiants Total</p>
                    <p class="text-4xl font-bold text-green-600 mt-2">{{ $students ?? 0 }}</p>
                </div>
                <span class="text-5xl text-green-200">👥</span>
            </div>
        </div>

        <!-- Quizzes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Quizzes Créés</p>
                    <p class="text-4xl font-bold text-purple-600 mt-2">{{ $quizzes ?? 0 }}</p>
                </div>
                <span class="text-5xl text-purple-200">🎯</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cours Récents -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">📖 Vos Cours</h2>
                    <a href="{{ route('teacher.courses.create') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-bold">
                        + Nouveau Cours
                    </a>
                </div>
                
                @if($recentCourses && $recentCourses->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentCourses as $course)
                            <div class="border-l-4 border-blue-500 bg-gray-50 p-4 rounded hover:bg-gray-100 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <a href="{{ route('courses.show', $course) }}" class="text-lg font-bold text-blue-600 hover:underline">
                                            {{ $course->title }}
                                        </a>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($course->description, 60) }}</p>
                                    </div>
                                    @if($course->is_published)
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-xs font-bold whitespace-nowrap ml-2">Publié</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded text-xs font-bold whitespace-nowrap ml-2">Brouillon</span>
                                    @endif
                                </div>
                                <div class="flex justify-between items-center text-sm text-gray-600">
                                    <span>📚 {{ $course->lessons()->count() }} leçons</span>
                                    <span>👥 {{ $course->students()->count() }} étudiants</span>
                                    <a href="{{ route('teacher.courses.edit', $course) }}" 
                                       class="text-blue-600 hover:underline font-semibold">Gérer →</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-center py-8">
                        Vous n'avez pas encore créé de cours.
                        <a href="{{ route('teacher.courses.create') }}" class="text-blue-600 hover:underline font-bold">Créer un cours</a>
                    </p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Actions Rapides -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">⚡ Actions Rapides</h3>
                <div class="space-y-2">
                    <a href="{{ route('teacher.courses.create') }}" 
                       class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                        + Nouveau Cours
                    </a>
                    <a href="{{ route('teacher.courses') }}" 
                       class="block w-full text-center bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition font-semibold">
                        Tous mes Cours
                    </a>
                    <a href="{{ route('profile.edit') }}" 
                       class="block w-full text-center bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition font-semibold">
                        Mon Profil
                    </a>
                </div>
            </div>

            <!-- Conseils -->
            <div class="bg-blue-50 rounded-lg border-l-4 border-blue-500 p-6">
                <h4 class="font-bold text-blue-900 mb-2">💡 Conseils</h4>
                <ul class="text-sm text-blue-800 space-y-2">
                    <li>✓ Créez des quizzes pour tester vos étudiants</li>
                    <li>✓ Publiez vos cours pour qu'ils soient visibles</li>
                    <li>✓ Utilisez les leçons gratuites pour promouvoir</li>
                    <li>✓ Vérifiez la progression de vos étudiants</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
