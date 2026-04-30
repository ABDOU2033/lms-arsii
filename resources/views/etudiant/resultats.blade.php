@extends('layouts.app')

@section('title', 'Mes Résultats')

@section('content')
<h1>Mes Résultats</h1>
@foreach($resultats as $coursTitre => $reponses)
    <h3>{{ $coursTitre }}</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Quiz</th>
                <th>Question</th>
                <th>Réponse</th>
                <th>Correcte</th>
                <th>Score</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reponses as $reponse)
                <tr>
                    <td>{{ $reponse->question->quiz->titre }}</td>
                    <td>{{ $reponse->question->enonce }}</td>
                    <td>{{ $reponse->contenu }}</td>
                    <td>{{ $reponse->est_correcte ? 'Oui' : 'Non' }}</td>
                    <td>{{ $reponse->score_obtenu }}</td>
                    <td>{{ $reponse->date_reponse->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
@endsection