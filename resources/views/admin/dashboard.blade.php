@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">Dashboard Administrateur</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-primary"></i>
                    <h5 class="card-title">{{ $totalUsers }}</h5>
                    <p class="card-text">Utilisateurs totaux</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-book fs-1 text-success"></i>
                    <h5 class="card-title">{{ $totalCours }}</h5>
                    <p class="card-text">Cours créés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-question-circle fs-1 text-warning"></i>
                    <h5 class="card-title">{{ $totalQuiz }}</h5>
                    <p class="card-text">Quiz créés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle fs-1 text-info"></i>
                    <h5 class="card-title">{{ $totalResponses }}</h5>
                    <p class="card-text">Réponses aux quiz</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Derniers utilisateurs inscrits</h5>
                </div>
                <div class="card-body">
                    @if($dernierUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Statut</th>
                                        <th>Date d'inscription</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dernierUsers as $user)
                                        <tr>
                                            <td>{{ $user->nom }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role == 'administrateur' ? 'danger' : ($user->role == 'enseignant' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $user->actif ? 'success' : 'secondary' }}">
                                                    {{ $user->actif ? 'Actif' : 'Inactif' }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucun utilisateur récent.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>Actions rapides</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.utilisateurs') }}" class="btn btn-primary d-block mb-2">
                        <i class="bi bi-people"></i> Gérer les utilisateurs
                    </a>
                    <a href="{{ route('admin.statistiques') }}" class="btn btn-outline-primary d-block mb-2">
                        <i class="bi bi-graph-up"></i> Voir les statistiques
                    </a>
                    <a href="{{ route('profil.show') }}" class="btn btn-outline-secondary d-block">
                        <i class="bi bi-person"></i> Mon profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection