@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Créer un Nouveau Cours</h1>

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

            <form method="POST" action="{{ route('teacher.courses.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Titre -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre du Cours *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                           placeholder="Ex: Introduction à Laravel" required>
                    @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea id="description" name="description" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                              placeholder="Décrivez le contenu et les objectifs du cours..."
                              required>{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Catégorie -->
                <div class="mb-6">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                           placeholder="Ex: Programmation">
                    @error('category') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Niveau -->
                <div class="mb-6">
                    <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Niveau *</label>
                    <select id="level" name="level"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            required>
                        <option value="">Sélectionnez un niveau...</option>
                        <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Débutant</option>
                        <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                        <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Avancé</option>
                    </select>
                    @error('level') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Thumbnail -->
                <div class="mb-6">
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Image du Cours</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-600 transition">
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                               class="hidden" onchange="previewImage(event)">
                        <label for="thumbnail" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-10l-3.172-3.172a4 4 0 00-5.656 0L28 21M12 28l-3.172-3.172a4 4 0 00-5.656 0L2 28m26-13l3.172-3.172a4 4 0 014.656 0L42 20M4 20h40" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <p class="text-gray-600">Cliquez pour télécharger une image</p>
                        </label>
                    </div>
                    <div id="preview" class="mt-2"></div>
                    @error('thumbnail') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Publié -->
                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="is_published" name="is_published" value="1"
                           {{ old('is_published') ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300 rounded">
                    <label for="is_published" class="ml-2 block text-sm text-gray-900">
                        Publier ce cours immédiatement
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
                        Créer le Cours
                    </button>
                </div>
            </form>
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
            preview.innerHTML = `<img src="${e.target.result}" class="w-32 h-32 object-cover rounded-lg">`;
        };
        reader.readAsDataURL(file);
    }
}
</script>
    </div>
</div>
@endsection
