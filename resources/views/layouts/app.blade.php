<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LMS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #185FA5;
            --purple-color: #533AB7;
            --green-color: #0F6E56;
            --coral-color: #993C1D;
        }
        .sidebar {
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #fff;
            border-right: 1px solid #e9ecef;
            z-index: 1000;
        }
        .main-content {
            margin-left: 240px;
            padding-top: 70px;
        }
        .topbar {
            position: fixed;
            top: 0;
            left: 240px;
            right: 0;
            height: 70px;
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #0e4a7a;
            border-color: #0e4a7a;
        }
        .progress-bar {
            background-color: var(--green-color);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h5 class="mb-4">LMS</h5>
        <ul class="nav flex-column">
            @if(auth()->check())
                @if(auth()->user()->isEtudiant())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiant.dashboard') }}"><i class="bi bi-house"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiant.catalogue') }}"><i class="bi bi-book"></i> Catalogue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiant.cours.index') }}"><i class="bi bi-journal"></i> Mes Cours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiant.resultats') }}"><i class="bi bi-trophy"></i> Résultats</a>
                    </li>
                @elseif(auth()->user()->isEnseignant())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enseignant.dashboard') }}"><i class="bi bi-house"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enseignant.cours.index') }}"><i class="bi bi-book"></i> Mes Cours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enseignant.resultats') }}"><i class="bi bi-trophy"></i> Résultats</a>
                    </li>
                @elseif(auth()->user()->isAdministrateur())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.utilisateurs') }}"><i class="bi bi-people"></i> Utilisateurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.statistiques') }}"><i class="bi bi-bar-chart"></i> Statistiques</a>
                    </li>
                @endif
            @endif
        </ul>
    </div>

    <!-- Topbar -->
    <div class="topbar">
        <div></div>
        <div class="d-flex align-items-center gap-3">
            @if(auth()->check())
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle" style="font-size: 24px;"></i>
                    <div>
                        <div style="font-weight: 600; color: #333;">{{ auth()->user()->nom }}</div>
                        <small style="color: #666;">
                            @if(auth()->user()->role === 'administrateur')
                                <span class="badge bg-danger">Admin</span>
                            @elseif(auth()->user()->role === 'enseignant')
                                <span class="badge bg-primary">Enseignant</span>
                            @else
                                <span class="badge bg-success">Étudiant</span>
                            @endif
                        </small>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content p-4">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
