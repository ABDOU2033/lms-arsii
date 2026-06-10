<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LMS - Connexion')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow: hidden;
            background: #0f1729;
        }

        /* ===== ANIMATED BACKGROUND ===== */
        .auth-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: linear-gradient(135deg, #0f1729 0%, #1a2744 40%, #0d2137 70%, #0f1729 100%);
            overflow: hidden;
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: floatOrb 8s ease-in-out infinite;
        }

        .orb-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(24,95,165,0.35) 0%, transparent 70%);
            top: -150px; left: -100px;
            animation-delay: 0s;
        }
        .orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(83,58,183,0.3) 0%, transparent 70%);
            bottom: -100px; right: -100px;
            animation-delay: 3s;
        }
        .orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(15,110,86,0.25) 0%, transparent 70%);
            top: 50%; left: 55%;
            animation-delay: 1.5s;
        }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.05); }
            66% { transform: translate(-20px, 15px) scale(0.95); }
        }

        /* Grid pattern overlay */
        .grid-overlay {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* Floating icons */
        .floating-icons {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .float-icon {
            position: absolute;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.3);
            font-size: 20px;
            animation: floatIcon linear infinite;
            backdrop-filter: blur(4px);
        }

        .float-icon:nth-child(1)  { left: 5%;  top: 15%; animation-duration: 14s; animation-delay: 0s;   }
        .float-icon:nth-child(2)  { left: 12%; top: 65%; animation-duration: 18s; animation-delay: -3s;  }
        .float-icon:nth-child(3)  { left: 20%; top: 35%; animation-duration: 12s; animation-delay: -6s;  }
        .float-icon:nth-child(4)  { left: 75%; top: 10%; animation-duration: 16s; animation-delay: -1s;  }
        .float-icon:nth-child(5)  { left: 85%; top: 50%; animation-duration: 20s; animation-delay: -8s;  }
        .float-icon:nth-child(6)  { left: 90%; top: 80%; animation-duration: 13s; animation-delay: -4s;  }
        .float-icon:nth-child(7)  { left: 60%; top: 85%; animation-duration: 17s; animation-delay: -2s;  }
        .float-icon:nth-child(8)  { left: 40%; top: 5%;  animation-duration: 15s; animation-delay: -7s;  }

        @keyframes floatIcon {
            0%   { transform: translateY(0px) rotate(0deg);   opacity: 0.4; }
            25%  { transform: translateY(-20px) rotate(5deg); opacity: 0.7; }
            50%  { transform: translateY(-10px) rotate(-3deg); opacity: 0.5; }
            75%  { transform: translateY(-25px) rotate(7deg); opacity: 0.8; }
            100% { transform: translateY(0px) rotate(0deg);   opacity: 0.4; }
        }

        /* Left panel - branding */
        .auth-left {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 60px;
            height: 100vh;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 60px;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #185FA5, #533AB7);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 8px 24px rgba(24,95,165,0.4);
        }

        .brand-name {
            font-size: 26px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
        }

        .brand-name span {
            color: #4e9ff5;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 800;
            color: white;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, #4e9ff5, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 16px;
            color: rgba(255,255,255,0.55);
            line-height: 1.7;
            max-width: 400px;
            margin-bottom: 48px;
        }

        /* Stats cards */
        .stats-row {
            display: flex;
            gap: 16px;
        }

        .stat-card {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 20px 24px;
            backdrop-filter: blur(12px);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-3px);
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: white;
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            margin-top: 4px;
        }

        .stat-icon {
            font-size: 18px;
            margin-bottom: 8px;
        }

        /* Features list */
        .feature-list {
            margin-top: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.7);
            font-size: 14px;
        }

        .feature-dot {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        /* Right panel - login form */
        .auth-right {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 60px;
            height: 100vh;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 24px;
            padding: 40px;
            backdrop-filter: blur(20px);
            box-shadow:
                0 25px 50px rgba(0,0,0,0.4),
                0 0 0 1px rgba(255,255,255,0.05) inset;
        }

        .login-card-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-card-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #185FA5, #533AB7);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin: 0 auto 16px;
            box-shadow: 0 12px 30px rgba(24,95,165,0.4);
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin-bottom: 6px;
        }

        .login-subtitle {
            font-size: 14px;
            color: rgba(255,255,255,0.45);
        }

        /* Form styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label-custom {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,0.7);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.35);
            font-size: 16px;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            color: white;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: rgba(255,255,255,0.25);
        }

        .form-input:focus {
            background: rgba(255,255,255,0.1);
            border-color: rgba(78,159,245,0.6);
            box-shadow: 0 0 0 3px rgba(78,159,245,0.15);
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #185FA5, #533AB7);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 8px;
            letter-spacing: 0.3px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(24,95,165,0.5);
        }

        .btn-login:hover::before { opacity: 1; }

        .btn-login:active { transform: translateY(0); }

        /* Alert styles */
        .alert-custom {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success-custom {
            background: rgba(15,110,86,0.2);
            border: 1px solid rgba(15,110,86,0.4);
            color: #4ade80;
        }

        .alert-danger-custom {
            background: rgba(153,60,29,0.2);
            border: 1px solid rgba(153,60,29,0.4);
            color: #f87171;
        }

        /* Register link */
        .register-link {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: rgba(255,255,255,0.4);
        }

        .register-link a {
            color: #4e9ff5;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .register-link a:hover { color: #a78bfa; }

        /* Divider */
        .divider {
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin: 24px 0;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .auth-left { display: none; }
            .auth-right { padding: 20px; }
            body { overflow: auto; }
        }
    </style>
</head>
<body>
    <!-- Background -->
    <div class="auth-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="grid-overlay"></div>

        <!-- Floating LMS icons -->
        <div class="floating-icons">
            <div class="float-icon"><i class="bi bi-book-half"></i></div>
            <div class="float-icon"><i class="bi bi-mortarboard"></i></div>
            <div class="float-icon"><i class="bi bi-journal-code"></i></div>
            <div class="float-icon"><i class="bi bi-trophy"></i></div>
            <div class="float-icon"><i class="bi bi-people"></i></div>
            <div class="float-icon"><i class="bi bi-bar-chart"></i></div>
            <div class="float-icon"><i class="bi bi-patch-check"></i></div>
            <div class="float-icon"><i class="bi bi-lightbulb"></i></div>
        </div>
    </div>

    <!-- Content -->
    <div class="container-fluid h-100" style="position:relative; z-index:1;">
        <div class="row h-100" style="min-height:100vh;">

            <!-- LEFT: Branding panel -->
            <div class="col-lg-6 auth-left">
                <div class="brand-logo">
                    <div class="brand-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div class="brand-name">LMS <span>ARSII</span></div>
                </div>

                <h1 class="hero-title">
                    Apprenez, <br>
                    <span class="highlight">Progressez</span><br>
                    &amp; Réussissez
                </h1>

                <p class="hero-subtitle">
                    La plateforme d'apprentissage en ligne qui connecte
                    étudiants et enseignants pour une expérience éducative
                    moderne et interactive.
                </p>

                <div class="stats-row mb-5">
                    <div class="stat-card">
                        <div class="stat-icon" style="color:#4e9ff5;">📚</div>
                        <div class="stat-number">{{ $nb_cours ?? 0 }}</div>
                        <div class="stat-label">Cours disponibles</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="color:#a78bfa;">👨‍🎓</div>
                        <div class="stat-number">{{ $nb_etudiants ?? 0 }}</div>
                        <div class="stat-label">Étudiants actifs</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="color:#4ade80;">🎓</div>
                        <div class="stat-number">{{ $nb_enseignants ?? 0 }}</div>
                        <div class="stat-label">Enseignants</div>
                    </div>
                </div>

                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-dot" style="background:rgba(78,159,245,0.15); color:#4e9ff5;">
                            <i class="bi bi-check2"></i>
                        </div>
                        Cours interactifs avec vidéos et quiz
                    </div>
                    <div class="feature-item">
                        <div class="feature-dot" style="background:rgba(167,139,250,0.15); color:#a78bfa;">
                            <i class="bi bi-check2"></i>
                        </div>
                        Suivi de progression en temps réel
                    </div>
                    <div class="feature-item">
                        <div class="feature-dot" style="background:rgba(74,222,128,0.15); color:#4ade80;">
                            <i class="bi bi-check2"></i>
                        </div>
                        Certificats et résultats instantanés
                    </div>
                </div>
            </div>

            <!-- RIGHT: Login form -->
            <div class="col-lg-6 auth-right">
                <div class="login-card">
                    @yield('content')
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
