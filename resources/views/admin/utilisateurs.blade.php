@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Gestion des Utilisateurs</h1>
                <div>
                    <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-primary me-2">
                        <i class="bi bi-person-plus"></i> Ajouter un Utilisateur
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour au dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="role" class="form-label">Filtrer par rôle</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">Tous les rôles</option>
                                <option value="etudiant" {{ request('role') == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                                <option value="enseignant" {{ request('role') == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                                <option value="administrateur" {{ request('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="search" class="form-label">Rechercher</label>
                            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Nom ou email">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-success btn-lg shadow-sm">
                            <i class="bi bi-person-plus-fill me-2"></i><strong>AJOUTER UN NOUVEL UTILISATEUR</strong>
                        </a>
                    </div>
                    @if($utilisateurs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Statut</th>
                                        <th>Date d'inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($utilisateurs as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
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
                                            <td>
                                                <a href="{{ route('admin.utilisateurs.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                @if($user->id !== auth()->id())
                                                    @if($user->actif)
                                                        <form action="{{ route('admin.utilisateurs.desactiver', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Désactiver cet utilisateur ?')" title="Désactiver">
                                                                <i class="bi bi-pause-circle"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.utilisateurs.activer', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Activer cet utilisateur ?')" title="Activer">
                                                                <i class="bi bi-play-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $utilisateurs->appends(request()->query())->links() }}
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted"></i>
                            <h5 class="mt-3">Aucun utilisateur trouvé</h5>
                            <p class="text-muted">Aucun utilisateur ne correspond aux critères de recherche.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection