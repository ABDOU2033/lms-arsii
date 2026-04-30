@extends('layouts.app')

@section('title', 'Dashboard Étudiant')

@section('content')
<h1>Dashboard Étudiant</h1>
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">{{ $coursInscrits }}</h5>
                <p class="card-text">Cours inscrits</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">{{ $quizPasses }}</h5>
                <p class="card-text">Quiz passés</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">{{ number_format($progressionMoy, 1) }}%</h5>
                <p class="card-text">Progression moyenne</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">{{ number_format($scoreMoy, 1) }}</h5>
                <p class="card-text">Score moyen</p>
            </div>
        </div>
    </div>
</div>
@endsection