@extends('layouts.app')

@section('title', 'Mes Cours')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Mes Cours</h1>
                <a href="{{ route('enseignant.cours.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouveau cours
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($cours->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Statut</th>
                                        <th>Étudiants</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cours as $cour)
                                        <tr>
                                            <td>{{ $cour->titre }}</td>
                                            <td>{{ Str::limit($cour->description, 50) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $cour->statut == 'publie' ? 'success' : ($cour->statut == 'brouillon' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($cour->statut) }}
                                                </span>
                                            </td>
                                            <td>{{ $cour->etudiants->count() }}</td>
                                            <td>
                                                <a href="{{ route('enseignant.cours.show', $cour) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('enseignant.cours.edit', $cour) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('enseignant.cours.destroy', $cour) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $cours->links() }}
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-book fs-1 text-muted"></i>
                            <h5 class="mt-3">Aucun cours créé</h5>
                            <p class="text-muted">Commencez par créer votre premier cours.</p>
                            <a href="{{ route('enseignant.cours.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Créer un cours
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection