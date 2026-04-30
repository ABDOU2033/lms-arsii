@extends('layouts.app')

@section('title', 'Créer une Question')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Créer une Question - {{ $quiz->titre }}</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h5><i class="bi bi-exclamation-triangle"></i> Erreurs de création :</h5>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('enseignant.question.store', $quiz) }}" method="POST" id="questionForm" onsubmit="return validateQuestionForm()">
                        @csrf

                        <div class="mb-3">
                            <label for="enonce" class="form-label">Énoncé <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('enonce') is-invalid @enderror" 
                                      id="enonce" name="enonce" rows="4" required>{{ old('enonce') }}</textarea>
                            @error('enonce')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                    id="type" name="type" onchange="updateChoixSection()" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="qcm" {{ old('type') === 'qcm' ? 'selected' : '' }}>QCM</option>
                                <option value="vrai_faux" {{ old('type') === 'vrai_faux' ? 'selected' : '' }}>Vrai/Faux</option>
                                <option value="texte_libre" {{ old('type') === 'texte_libre' ? 'selected' : '' }}>Texte libre</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="choixSection" style="display: none;">
                            <label class="form-label">Choix de Réponses <span class="text-danger">*</span></label>
                            <div id="choixContainer">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="Réponse 1" name="choix[0][contenu]">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" name="choix[0][est_correcte]" value="1">
                                        <span class="ms-2">Correcte</span>
                                    </div>
                                </div>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="Réponse 2" name="choix[1][contenu]">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" name="choix[1][est_correcte]" value="1">
                                        <span class="ms-2">Correcte</span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addChoix()">
                                <i class="bi bi-plus-circle"></i> Ajouter Choix
                            </button>

                            <div class="mt-3" id="correctVraiFauxSection" style="display:none;">
                                <label class="form-label">Bonne réponse Vrai/Faux <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_vrai_faux" id="correctVrai" value="Vrai" {{ old('correct_vrai_faux', 'Vrai') === 'Vrai' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="correctVrai">Vrai</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_vrai_faux" id="correctFaux" value="Faux" {{ old('correct_vrai_faux') === 'Faux' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="correctFaux">Faux</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="points" class="form-label">Points <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                   id="points" name="points" value="{{ old('points', 1) }}" min="1" required>
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-check-circle"></i> Créer
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
let choixCount = 2;

function updateChoixSection() {
    const type = document.getElementById('type').value;
    const choixSection = document.getElementById('choixSection');
    const correctVraiFauxSection = document.getElementById('correctVraiFauxSection');

    if (type === 'qcm') {
        correctVraiFauxSection.style.display = 'none';
        choixSection.style.display = 'block';
        document.getElementById('choixContainer').innerHTML = `
            <div class="input-group mb-2">
                <input type="text" class="form-control" placeholder="Réponse 1" name="choix[0][contenu]">
                <div class="input-group-text">
                    <input class="form-check-input mt-0" type="checkbox" name="choix[0][est_correcte]" value="1">
                    <span class="ms-2">Correcte</span>
                </div>
            </div>
            <div class="input-group mb-2">
                <input type="text" class="form-control" placeholder="Réponse 2" name="choix[1][contenu]">
                <div class="input-group-text">
                    <input class="form-check-input mt-0" type="checkbox" name="choix[1][est_correcte]" value="1">
                    <span class="ms-2">Correcte</span>
                </div>
            </div>
        `;
        choixCount = 2;
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
        document.getElementById('correctVrai').checked = true;
        document.getElementById('correctFaux').checked = false;
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

function validateQuestionForm() {
    const type = document.getElementById('type').value;
    
    if (type === 'qcm') {
        // Vérifier qu'il y a au moins 2 choix valides (non vides)
        const choixInputs = document.querySelectorAll('input[name^="choix["][name$="[contenu]"]');
        let validChoixCount = 0;
        choixInputs.forEach(input => {
            if (input.value.trim() !== '') {
                validChoixCount++;
            }
        });
        
        if (validChoixCount < 2) {
            alert('Un QCM doit contenir au moins 2 choix de réponse non vides.');
            return false;
        }
        
        // Vérifier qu'il y a au moins une réponse correcte
        const correctCheckboxes = document.querySelectorAll('input[name^="choix["][name$="[est_correcte]"]:checked');
        if (correctCheckboxes.length === 0) {
            alert('Vous devez cocher au moins une réponse comme correcte pour un QCM.');
            return false;
        }
    }
    
    return true;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateChoixSection);
</script>
@endsection
