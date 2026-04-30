@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Modifier une Leçon</h1>
            <p class="text-gray-600 mb-6">Cours: <strong>{{ $course->title }}</strong></p>

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

            <form method="POST" action="{{ route('teacher.lessons.update', [$course, $lesson]) }}">
                @csrf
                @method('PUT')

                <!-- Titre -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre de la Leçon *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $lesson->title) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                           required>
                    @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Contenu -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Contenu *</label>
                    <textarea id="content" name="content" rows="10"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                              required>{{ old('content', $lesson->content) }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Vous pouvez utiliser du texte brut. Les retours à la ligne seront conservés.</p>
                    @error('content') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Ordre -->
                <div class="mb-6">
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Ordre d'Apparition *</label>
                    <input type="number" id="order" name="order" value="{{ old('order', $lesson->order) }}" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                           required>
                    @error('order') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Gratuit -->
                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="is_free" name="is_free" value="1"
                           {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300 rounded">
                    <label for="is_free" class="ml-2 block text-sm text-gray-900">
                        Cette leçon est gratuite (visible sans inscription)
                    </label>
                </div>

                <!-- Boutons -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('lessons.show', [$course, $lesson]) }}"
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
</div>
@endsection
