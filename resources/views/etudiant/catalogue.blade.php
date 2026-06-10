@extends('layouts.app')

@section('title', 'Catalogue des Cours')

@section('content')
<h1>Catalogue des Cours</h1>
<div class="row">
    @foreach($cours as $cour)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $cour->titre }}</h5>
                    <p class="card-text">{{ Str::limit($cour->description, 100) }}</p>
                    <p class="text-muted">Par {{ $cour->enseignant->user->nom }}</p>
                    
                    @if(Auth::user()->etudiant && Auth::user()->etudiant->cours()->where('cours_id', $cour->id)->exists())
                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Déjà inscrit</span>
                    @else
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInscrire{{ $cour->id }}">
                            <i class="bi bi-key"></i> S'inscrire
                        </button>
                        
                        <div class="modal fade" id="modalInscrire{{ $cour->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('etudiant.cours.inscrire', $cour) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Inscription : {{ $cour->titre }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="cle_{{ $cour->id }}" class="form-label">Entrez la clé d'inscription <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="cle_{{ $cour->id }}" name="cle_inscription" 
                                                   placeholder="Ex: A3F8B2C1" maxlength="10" required style="text-transform: uppercase;">
                                            @error('cle_inscription')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
{{ $cours->links() }}
@endsection
