@extends('layouts.app')

@section('title', 'Réponses aux Quiz')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3">Réponses aux Quiz ({{ $reponses->total() }})</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" action="{{ route('admin.reponses') }}" class="form-inline">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par étudiant ou quiz" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ml-2">Rechercher</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Étudiant</th>
                            <th>Quiz</th>
                            <th>Question</th>
                            <th>Contenu</th>
                            <th>Correcte</th>
                            <th>Score</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reponses as $reponse)
                            <tr>
                                <td>{{ $reponse->etudiant->nom ?? 'N/A' }}</td>
                                <td>{{ $reponse->question->quiz->titre ?? 'N/A' }}</td>
                                <td>{{ Str::limit($reponse->question->contenu ?? 'N/A', 50) }}</td>
                                <td>{{ Str::limit($reponse->contenu, 50) }}</td>
                                <td>
                                    @if($reponse->est_correcte)
                                        <span class="badge bg-success">Oui</span>
                                    @else
                                        <span class="badge bg-danger">Non</span>
                                    @endif
                                </td>
                                <td>{{ $reponse->score_obtenu ?? 0 }}%</td>
                                <td>{{ $reponse->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucune réponse trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $reponses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
