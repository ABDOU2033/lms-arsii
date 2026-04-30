@extends('layouts.app')

@section('title', 'Mes Cours')

@section('content')
<h1>Mes Cours</h1>
<div class="row">
    @foreach($cours as $cour)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $cour->titre }}</h5>
                    <p class="card-text">{{ Str::limit($cour->description, 100) }}</p>
                    <div class="mb-3">
                        <small>Progression</small>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $cour->progressions->where('etudiant_id', auth()->user()->etudiant->id)->first()->pourcentage ?? 0 }}%"></div>
                        </div>
                    </div>
                    <a href="{{ route('etudiant.cours.show', $cour) }}" class="btn btn-primary">Accéder</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection