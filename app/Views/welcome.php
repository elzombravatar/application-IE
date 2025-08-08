<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Groupe IE' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Styles personnalisés -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .welcome-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            margin: 0 auto;
        }

        .logo-container {
            margin-bottom: 2rem;
        }

        .logo {
            width: 150px;
            height: 150px;
            margin: 0 auto;
            background: linear-gradient(45deg, #6B46C1, #9333EA);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(107, 70, 193, 0.3);
            animation: rotate 8s linear infinite;
            position: relative;
            overflow: hidden;
        }

        .logo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shine 3s ease-in-out infinite;
        }

        .logo-text {
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            z-index: 1;
            position: relative;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            50% { transform: translateX(100%) translateY(100%) rotate(45deg); }
            100% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-title {
            color: #333;
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .welcome-subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.7s both;
        }

        .login-btn {
            background: linear-gradient(45deg, #6B46C1, #9333EA);
            border: none;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(107, 70, 193, 0.4);
            animation: fadeInUp 1s ease-out 0.9s both;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(107, 70, 193, 0.6);
            color: white;
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .company-info {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 0.9rem;
            animation: fadeInUp 1s ease-out 1.1s both;
        }

        /* Particules en arrière-plan */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) { width: 4px; height: 4px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 6px; height: 6px; left: 20%; animation-delay: 1s; }
        .particle:nth-child(3) { width: 3px; height: 3px; left: 30%; animation-delay: 2s; }
        .particle:nth-child(4) { width: 5px; height: 5px; left: 40%; animation-delay: 3s; }
        .particle:nth-child(5) { width: 4px; height: 4px; left: 50%; animation-delay: 4s; }
        .particle:nth-child(6) { width: 6px; height: 6px; left: 60%; animation-delay: 5s; }
        .particle:nth-child(7) { width: 3px; height: 3px; left: 70%; animation-delay: 6s; }
        .particle:nth-child(8) { width: 5px; height: 5px; left: 80%; animation-delay: 7s; }
        .particle:nth-child(9) { width: 4px; height: 4px; left: 90%; animation-delay: 8s; }

        @keyframes float {
            0%, 100% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10%, 90% { opacity: 1; }
            50% { transform: translateY(-10vh) rotate(180deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .logo {
                width: 120px;
                height: 120px;
            }
            
            .logo-text {
                font-size: 2rem;
            }
            
            .welcome-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Particules animées -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="welcome-container">
        <!-- Logo animé -->
        <div class="logo-container">
            <div class="logo">
                <div class="logo-text">IE</div>
            </div>
        </div>

        <!-- Contenu -->
        <h1 class="welcome-title">Bienvenue sur la Plateforme</h1>
        <p class="welcome-subtitle">
            Plateforme de gestion des Fiches d'Identification des Déchets<br>
            et de suivi Trackdéchets
        </p>

        <!-- Bouton de connexion -->
        <a href="<?= base_url('auth/login') ?>" class="login-btn">
            🔐 Se connecter
        </a>

        <!-- Informations entreprise -->
        <div class="company-info">
            <strong>Groupe IE</strong><br>
            Spécialiste du transport de déchets dangereux<br>
            <small>Version 1.0 • <?= date('Y') ?></small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Animation au clic du logo -->
    <script>
        document.querySelector('.logo').addEventListener('click', function() {
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = 'rotate 2s linear infinite';
            }, 100);
        });

        // Effet de parallaxe léger
        document.addEventListener('mousemove', function(e) {
            const logo = document.querySelector('.logo');
            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;
            
            logo.style.transform = `rotate(${logo.style.transform.replace(/[^\d]/g, '') || 0}deg) translate(${x}px, ${y}px)`;
        });
    </script>
</body>
</html>