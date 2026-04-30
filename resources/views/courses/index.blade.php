@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12 mb-8">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-2">Découvrez nos Cours</h1>
            <p class="text-lg opacity-90">Apprenez de nouveaux compétences avec nos cours professionnels</p>
        </div>
    </div>

    <div class="container mx-auto px-4">
        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg border border-green-400 flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 p-4 bg-blue-100 text-blue-700 rounded-lg border border-blue-400">
                {{ session('info') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-400">
                {{ session('error') }}
            </div>
        @endif

        <!-- Grille des Cours -->
        @if($courses->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($courses as $course)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition overflow-hidden">
                        <!-- Thumbnail -->
                        <div class="relative h-48 bg-gray-200">
                            @if($course->thumbnail)
                                <img src="{{ Storage::url($course->thumbnail) }}" 
                                     alt="{{ $course->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600">
                                    <svg class="w-20 h-20 text-white opacity-40" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.5 1.5H5.75A2.25 2.25 0 0 0 3.5 3.75v12.5A2.25 2.25 0 0 0 5.75 18.5h8.5a2.25 2.25 0 0 0 2.25-2.25V6.5M10 11v-2m2 2l-2-2m0 0L8 13"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            <div class="absolute top-3 right-3 flex gap-2">
                                <span class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-full">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </div>

                            @if(in_array($course->id, $enrolledIds))
                                <div class="absolute bottom-3 left-3">
                                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">
                                        ✓ Inscrit
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Contenu -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">{{ $course->title }}</h3>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $course->description }}
                            </p>

                            <!-- Enseignant -->
                            <div class="text-sm text-gray-500 mb-4">
                                Par: <strong class="text-gray-700">{{ $course->teacher->name }}</strong>
                            </div>

                            <!-- Boutons -->
                            <div class="flex gap-2 mb-3">
                                <a href="{{ route('courses.show', $course) }}" 
                                   class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-center text-sm hover:bg-blue-700 transition font-medium">
                                    Détails
                                </a>

                                @auth
                                    @if(in_array($course->id, $enrolledIds))
                                        <form method="POST" action="{{ route('courses.unenroll', $course) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full px-3 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition font-medium">
                                                Quitter
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('courses.enroll', $course) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full px-3 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700 transition font-medium">
                                                S'inscrire
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="flex-1 px-3 py-2 bg-green-600 text-white rounded text-center text-sm hover:bg-green-700 transition font-medium">
                                        Connexion
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mb-8 flex justify-center">
                {{ $courses->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-lg">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun cours disponible</h3>
                <p class="text-gray-500">Revenez bientôt pour découvrir de nouveaux cours.</p>
            </div>
        @endif
    </div>
</div>
@endsection
