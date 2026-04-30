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
                    <form method="POST" action="{{ route('etudiant.cours.inscrire', $cour) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
{{ $cours->links() }}
@endsection