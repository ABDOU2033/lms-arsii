@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mes Cours Inscrits</h1>
            <a href="{{ route('courses.index') }}" 
               class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                Découvrir plus de cours
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($courses as $course)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                        <!-- Thumbnail avec progress bar -->
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
                            
                            <!-- Progress Overlay -->
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-300">
                                <div class="h-full bg-green-500" style="width: {{ $course->pivot->progress }}%"></div>
                            </div>
                        </div>

                        <!-- Contenu -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3">
                                {{ Str::limit($course->description, 80) }}
                            </p>

                            <!-- Enseignant -->
                            <div class="text-sm text-gray-600 mb-4">
                                Par: <strong>{{ $course->teacher->name }}</strong>
                            </div>

                            <!-- Progress -->
                            <div class="mb-4 py-3 border-t border-b">
                                <p class="text-sm font-medium text-gray-700 mb-1">Progression</p>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full transition" style="width: {{ $course->pivot->progress }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">{{ $course->pivot->progress }}%</span>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="flex gap-2">
                                <a href="{{ route('courses.show', $course) }}" 
                                   class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-center text-sm hover:bg-blue-700 transition font-medium">
                                    Continuer
                                </a>
                                <form method="POST" action="{{ route('courses.unenroll', $course) }}" class="flex-1" onsubmit="return confirm('Êtes-vous sûr de vouloir vous désinscrire?');">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-3 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition font-medium">
                                        Quitter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $courses->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-lg">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun cours suivi</h3>
                <p class="text-gray-500 mb-6">Vous n'êtes inscrit à aucun cours pour le moment.</p>
                <a href="{{ route('courses.index') }}" 
                   class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Parcourir les Cours
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
