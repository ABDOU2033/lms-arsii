@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Inscription</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-control" id="role" name="role" required onchange="toggleFields()">
                                <option value="">Choisir un rôle</option>
                                <option value="etudiant">Étudiant</option>
                                <option value="enseignant">Enseignant</option>
                            </select>
                        </div>
                        <div id="etudiant-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="niveau" class="form-label">Niveau</label>
                                <input type="text" class="form-control" id="niveau" name="niveau">
                            </div>
                        </div>
                        <div id="enseignant-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="specialite" class="form-label">Spécialité</label>
                                <input type="text" class="form-control" id="specialite" name="specialite">
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio"></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mot_de_passe" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                        </div>
                        <div class="mb-3">
                            <label for="mot_de_passe_confirmation" class="form-label">Confirmer mot de passe</label>
                            <input type="password" class="form-control" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </form>
                    <p class="mt-3">Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFields() {
    const role = document.getElementById('role').value;
    document.getElementById('etudiant-fields').style.display = role === 'etudiant' ? 'block' : 'none';
    document.getElementById('enseignant-fields').style.display = role === 'enseignant' ? 'block' : 'none';
}
</script>
@endsection
