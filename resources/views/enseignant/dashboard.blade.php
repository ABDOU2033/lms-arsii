@extends('layouts.app')

@section('title', 'Dashboard Enseignant')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">Dashboard Enseignant</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-book fs-1 text-primary"></i>
                    <h5 class="card-title">{{ $cours }}</h5>
                    <p class="card-text">Cours créés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-success"></i>
                    <h5 class="card-title">{{ $etudiants }}</h5>
                    <p class="card-text">Étudiants inscrits</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-question-circle fs-1 text-warning"></i>
                    <h5 class="card-title">{{ $quiz }}</h5>
                    <p class="card-text">Quiz créés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-bar-chart fs-1 text-info"></i>
                    <h5 class="card-title">{{ number_format($progressionMoy, 1) }}%</h5>
                    <p class="card-text">Progression moyenne</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('enseignant.cours.create') }}" class="btn btn-primary me-2">
                        <i class="bi bi-plus-circle"></i> Créer un cours
                    </a>
                    <a href="{{ route('enseignant.cours.index') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-list"></i> Gérer mes cours
                    </a>
                    <a href="{{ route('enseignant.resultats') }}" class="btn btn-outline-success">
                        <i class="bi bi-graph-up"></i> Voir les résultats
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection