@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mes Cours</h1>
            <a href="{{ route('teacher.courses.create') }}" 
               class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                + Nouveau Cours
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-400 flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($courses->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                        <!-- Thumbnail -->
                        <div class="relative h-40 bg-gray-200">
                            @if($course->thumbnail)
                                <img src="{{ Storage::url($course->thumbnail) }}" 
                                     alt="{{ $course->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600">
                                    <svg class="w-16 h-16 text-white opacity-40" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 {{ $course->is_published ? 'bg-green-500' : 'bg-yellow-500' }} text-white text-xs font-bold rounded-full">
                                    {{ $course->is_published ? '✓ Publié' : 'Brouillon' }}
                                </span>
                            </div>
                        </div>

                        <!-- Contenu -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3">
                                {{ Str::limit($course->description, 80) }}
                            </p>

                            <!-- Stats -->
                            <div class="flex gap-4 text-sm text-gray-600 mb-4 py-2 border-t border-b">
                                <div>
                                    <span class="font-bold text-gray-800">{{ $course->lessons->count() }}</span> leçons
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800">{{ $course->students()->count() }}</span> étudiants
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="flex gap-2">
                                <a href="{{ route('teacher.courses.edit', $course) }}" 
                                   class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-center text-sm hover:bg-blue-700 transition font-medium">
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" class="flex-1" onsubmit="return confirm('Êtes-vous sûr?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full px-3 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition font-medium">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $courses->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-lg">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun cours créé</h3>
                <p class="text-gray-500 mb-6">Commencez par créer votre premier cours.</p>
                <a href="{{ route('teacher.courses.create') }}" 
                   class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Créer un Cours
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
