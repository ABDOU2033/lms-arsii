@extends('layouts.app')

@section('title', 'Tableau de bord - Admin')

@section('content')
<div class="container mx-auto py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">🛡️ Tableau de bord Administrateur</h1>
        <p class="text-gray-600">Gérez le système et les utilisateurs</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Utilisateurs</p>
                    <p class="text-4xl font-bold text-blue-600 mt-2">{{ $totalUsers ?? 0 }}</p>
                </div>
                <span class="text-5xl text-blue-200">👥</span>
            </div>
        </div>

        <!-- Teachers -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Enseignants</p>
                    <p class="text-4xl font-bold text-green-600 mt-2">{{ $totalTeachers ?? 0 }}</p>
                </div>
                <span class="text-5xl text-green-200">👨‍🏫</span>
            </div>
        </div>

        <!-- Students -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Étudiants</p>
                    <p class="text-4xl font-bold text-purple-600 mt-2">{{ $totalStudents ?? 0 }}</p>
                </div>
                <span class="text-5xl text-purple-200">👨‍🎓</span>
            </div>
        </div>

        <!-- Courses -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Cours</p>
                    <p class="text-4xl font-bold text-orange-600 mt-2">{{ $totalCourses ?? 0 }}</p>
                </div>
                <span class="text-5xl text-orange-200">📚</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Utilisateurs Récents -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">👥 Utilisateurs Récents</h2>
            
            @if($recentUsers && $recentUsers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 px-2 font-bold text-gray-600">Nom</th>
                                <th class="text-left py-2 px-2 font-bold text-gray-600">Email</th>
                                <th class="text-left py-2 px-2 font-bold text-gray-600">Rôle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-2 px-2 font-semibold text-gray-800">{{ $user->name }}</td>
                                    <td class="py-2 px-2 text-gray-600">{{ $user->email }}</td>
                                    <td class="py-2 px-2">
                                        @if($user->role === 'admin')
                                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold">Admin</span>
                                        @elseif($user->role === 'teacher')
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">Prof</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Étudiant</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-center py-6">Aucun utilisateur</p>
            @endif
        </div>

        <!-- Cours Récents -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">📖 Cours Récents</h2>
            
            @if($recentCourses && $recentCourses->count() > 0)
                <div class="space-y-3">
                    @foreach($recentCourses as $course)
                        <div class="border-l-4 border-blue-500 bg-gray-50 p-4 rounded hover:bg-gray-100 transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800">{{ $course->title }}</p>
                                    <p class="text-sm text-gray-600">Prof: {{ $course->teacher->name ?? 'N/A' }}</p>
                                </div>
                                @if($course->is_published)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Publié</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-bold">Brouillon</span>
                                @endif
                            </div>
                            <div class="flex gap-4 text-xs text-gray-600 mt-2">
                                <span>📚 {{ $course->lessons()->count() }} leçons</span>
                                <span>👥 {{ $course->students()->count() }} étudiants</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 text-center py-6">Aucun cours récent</p>
            @endif
        </div>
    </div>

    <!-- System Info -->
    <div class="mt-8 bg-blue-50 rounded-lg border-l-4 border-blue-500 p-6">
        <h3 class="text-lg font-bold text-blue-900 mb-3">ℹ️ Informations Système</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm text-blue-800">
            <div>
                <p class="text-gray-600 text-xs">Framework</p>
                <p class="font-bold">Laravel 12</p>
            </div>
            <div>
                <p class="text-gray-600 text-xs">PHP Version</p>
                <p class="font-bold">{{ phpversion() }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-xs">Ratio Prof/Étudiants</p>
                <p class="font-bold">1:{{ $totalTeachers > 0 ? round($totalStudents / $totalTeachers) : 0 }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-xs">Moyenne Cours par Prof</p>
                <p class="font-bold">{{ $totalTeachers > 0 ? round($totalCourses / $totalTeachers) : 0 }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-xs">Statut</p>
                <p class="font-bold text-green-600">✓ En ligne</p>
            </div>
        </div>
    </div>
</div>
@endsection
