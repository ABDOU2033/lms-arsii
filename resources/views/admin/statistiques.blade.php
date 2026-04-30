@extends('layouts.app')

@section('title', 'Statistiques Globales')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Statistiques Globales</h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour au dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-primary"></i>
                    <h5 class="card-title">{{ $stats['total_users'] }}</h5>
                    <p class="card-text">Utilisateurs totaux</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-person-check fs-1 text-success"></i>
                    <h5 class="card-title">{{ $stats['active_users'] }}</h5>
                    <p class="card-text">Utilisateurs actifs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-book fs-1 text-warning"></i>
                    <h5 class="card-title">{{ $stats['total_cours'] }}</h5>
                    <p class="card-text">Cours totaux</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-book-check fs-1 text-info"></i>
                    <h5 class="card-title">{{ $stats['published_cours'] }}</h5>
                    <p class="card-text">Cours publiés</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-question-circle fs-1 text-secondary"></i>
                    <h5 class="card-title">{{ $stats['total_quiz'] }}</h5>
                    <p class="card-text">Quiz créés</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle fs-1 text-danger"></i>
                    <h5 class="card-title">{{ $stats['total_responses'] }}</h5>
                    <p class="card-text">Réponses aux quiz</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Répartition des utilisateurs par rôle</h5>
                </div>
                <div class="card-body">
                    <canvas id="roleChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Évolution des inscriptions</h5>
                </div>
                <div class="card-body">
                    <canvas id="inscriptionChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleData = {
        labels: ['Étudiants', 'Enseignants', 'Administrateurs'],
        datasets: [{
            data: [{{ $stats['etudiants'] }}, {{ $stats['enseignants'] }}, {{ $stats['administrateurs'] }}],
            backgroundColor: ['#0d6efd', '#fd7e14', '#dc3545']
        }]
    };

    const totalRoles = roleData.datasets[0].data.reduce((a, b) => a + b, 0);
    if (totalRoles === 0) {
        document.getElementById('roleChart').parentElement.innerHTML = '<div class="alert alert-info">Aucun utilisateur trouvé.</div>';
        return;
    }

    const roleChart = new Chart(document.getElementById('roleChart'), {
        type: 'pie',
        data: roleData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Graphique d'évolution des inscriptions (données réelles)
    const inscriptionData = {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [{
            label: 'Nouvelles inscriptions',
            data: {!! json_encode($stats['inscriptions_par_mois']) !!},
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4
        }]
    };

    const inscriptionChart = new Chart(document.getElementById('inscriptionChart'), {        type: 'line',
        data: inscriptionData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection