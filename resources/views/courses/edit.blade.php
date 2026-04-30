@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulaire d'édition du cours -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Modifier le Cours</h1>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg border border-red-400">
                            <h3 class="font-bold mb-2">Erreurs:</h3>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('teacher.courses.update', $course) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Titre -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre du Cours *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $course->title) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                   required>
                            @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea id="description" name="description" rows="5"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                      required>{{ old('description', $course->description) }}</textarea>
                            @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Catégorie -->
                        <div class="mb-6">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                            <input type="text" id="category" name="category" value="{{ old('category', $course->category) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                            @error('category') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Niveau -->
                        <div class="mb-6">
                            <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Niveau *</label>
                            <select id="level" name="level"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                    required>
                                <option value="">Sélectionnez un niveau...</option>
                                <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Débutant</option>
                                <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Avancé</option>
                            </select>
                            @error('level') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Thumbnail -->
                        <div class="mb-6">
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Image du Cours</label>
                            
                            @if($course->thumbnail)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Image actuelle:</p>
                                    <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-40 h-32 object-cover rounded-lg">
                                </div>
                            @endif

                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-600 transition">
                                <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                                       class="hidden" onchange="previewImage(event)">
                                <label for="thumbnail" class="cursor-pointer">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-10l-3.172-3.172a4 4 0 00-5.656 0L28 21M12 28l-3.172-3.172a4 4 0 00-5.656 0L2 28m26-13l3.172-3.172a4 4 0 014.656 0L42 20M4 20h40" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <p class="text-gray-600">Cliquez pour télécharger une nouvelle image</p>
                                </label>
                            </div>
                            <div id="preview" class="mt-2"></div>
                            @error('thumbnail') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Publié -->
                        <div class="mb-6 flex items-center">
                            <input type="checkbox" id="is_published" name="is_published" value="1"
                                   {{ old('is_published', $course->is_published) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300 rounded">
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">
                                Publier ce cours
                            </label>
                        </div>

                        <!-- Boutons -->
                        <div class="flex gap-4 justify-end">
                            <a href="{{ route('teacher.courses') }}"
                               class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                                Annuler
                            </a>
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                Mettre à Jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Gestion des leçons (sidebar) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-20">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Leçons</h2>
                        <a href="{{ route('teacher.lessons.create', $course) }}" 
                           class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                            + Ajouter
                        </a>
                    </div>

                    @if($course->lessons->count())
                        <div class="space-y-2 max-h-96 overflow-y-auto">
                            @foreach($course->lessons as $lesson)
                                <div class="border border-gray-200 rounded p-3 hover:bg-gray-50">
                                    <p class="font-medium text-gray-900 text-sm">{{ $lesson->title }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Ordre: {{ $lesson->order }}</p>
                                    <div class="flex gap-1 mt-2">
                                        <a href="{{ route('teacher.lessons.edit', [$course, $lesson]) }}" 
                                           class="flex-1 px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600 text-center">
                                            Modifier
                                        </a>
                                        <form method="POST" action="{{ route('teacher.lessons.destroy', [$course, $lesson]) }}" class="flex-1" onsubmit="return confirm('Êtes-vous sûr?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                                Supp.
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-gray-600 text-sm mb-3">Aucune leçon créée</p>
                            <a href="{{ route('teacher.lessons.create', $course) }}" 
                               class="block px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                Créer la Première Leçon
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<p class="text-sm text-gray-600 mb-2">Nouvel aperçu:</p><img src="${e.target.result}" class="w-40 h-32 object-cover rounded-lg">`;
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
