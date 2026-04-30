@extends('layouts.app')

@section('title', 'Éditer Question')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Éditer la Question</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('enseignant.question.update', $question) }}" method="POST" id="questionForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="enonce" class="form-label">Énoncé <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('enonce') is-invalid @enderror" 
                                      id="enonce" name="enonce" rows="4" required>{{ old('enonce', $question->enonce) }}</textarea>
                            @error('enonce')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                    id="type" name="type" onchange="updateChoixSection()" required>
                                <option value="qcm" {{ old('type', $question->type) === 'qcm' ? 'selected' : '' }}>QCM</option>
                                <option value="vrai_faux" {{ old('type', $question->type) === 'vrai_faux' ? 'selected' : '' }}>Vrai/Faux</option>
                                <option value="texte_libre" {{ old('type', $question->type) === 'texte_libre' ? 'selected' : '' }}>Texte libre</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="choixSection" style="display: none;">
                            <label class="form-label">Choix de Réponses <span class="text-danger">*</span></label>
                            <div id="choixContainer">
                                @forelse($choixReponses as $key => $choix)
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" value="{{ $choix->contenu }}" name="choix[{{ $key }}][contenu]">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="checkbox" name="choix[{{ $key }}][est_correcte]" 
                                                   value="1" {{ $choix->est_correcte ? 'checked' : '' }}>
                                            <span class="ms-2">Correcte</span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                @empty
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" placeholder="Réponse 1" name="choix[0][contenu]">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="checkbox" name="choix[0][est_correcte]" value="1">
                                            <span class="ms-2">Correcte</span>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addChoix()">
                                <i class="bi bi-plus-circle"></i> Ajouter Choix
                            </button>

                            <div class="mt-3" id="correctVraiFauxSection" style="display:none;">
                                <label class="form-label">Bonne réponse Vrai/Faux <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_vrai_faux" id="correctVrai" value="Vrai" {{ old('correct_vrai_faux', $choixReponses->firstWhere('est_correcte', true)->contenu ?? 'Vrai') === 'Vrai' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="correctVrai">Vrai</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_vrai_faux" id="correctFaux" value="Faux" {{ old('correct_vrai_faux', $choixReponses->firstWhere('est_correcte', true)->contenu ?? 'Vrai') === 'Faux' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="correctFaux">Faux</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="points" class="form-label">Points <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                   id="points" name="points" value="{{ old('points', $question->points) }}" min="1" required>
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-check-circle"></i> Mettre à Jour
                            </button>
                            <a href="{{ route('enseignant.quiz.show', $quiz) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let choixCount = {{ $choixReponses->count() }};
const initialCorrectVraiFaux = '{{ old('correct_vrai_faux', $question->type === 'vrai_faux' ? ($choixReponses->firstWhere('est_correcte', true)->contenu ?? 'Vrai') : 'Vrai') }}';

function updateChoixSection() {
    const type = document.getElementById('type').value;
    const choixSection = document.getElementById('choixSection');
    const correctVraiFauxSection = document.getElementById('correctVraiFauxSection');

    if (type === 'qcm') {
        choixSection.style.display = 'block';
        correctVraiFauxSection.style.display = 'none';
        // For qcm we keep existing rows loaded from server
    } else if (type === 'vrai_faux') {
        choixSection.style.display = 'block';
        correctVraiFauxSection.style.display = 'block';
        document.getElementById('choixContainer').innerHTML = `
            <div class="input-group mb-2">
                <input type="text" class="form-control" value="Vrai" readonly>
                <input type="hidden" name="choix[0][contenu]" value="Vrai">
            </div>
            <div class="input-group mb-2">
                <input type="text" class="form-control" value="Faux" readonly>
                <input type="hidden" name="choix[1][contenu]" value="Faux">
            </div>
        `;

        if (initialCorrectVraiFaux === 'Faux') {
            document.getElementById('correctFaux').checked = true;
            document.getElementById('correctVrai').checked = false;
        } else {
            document.getElementById('correctVrai').checked = true;
            document.getElementById('correctFaux').checked = false;
        }
    } else {
        choixSection.style.display = 'none';
        correctVraiFauxSection.style.display = 'none';
    }
}

function addChoix() {
    const container = document.getElementById('choixContainer');
    const html = `
        <div class="input-group mb-2">
            <input type="text" class="form-control" placeholder="Réponse ${choixCount + 1}" name="choix[${choixCount}][contenu]">
            <div class="input-group-text">
                <input class="form-check-input mt-0" type="checkbox" name="choix[${choixCount}][est_correcte]" value="1">
                <span class="ms-2">Correcte</span>
            </div>
            <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    choixCount++;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateChoixSection);
</script>
@endsection
