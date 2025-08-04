<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Application IE – Portail</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpH+9Wf5UknGx3z51WjKzO8q3BzFVZ10lG8cdgCkGZkV2tgs40L0a" crossorigin="anonymous">

    <!-- Style perso -->
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 100px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 30px;
        }
        .btn-app {
            margin: 10px;
            padding: 20px;
            font-size: 1.2rem;
            width: 250px;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <!-- Logo aléatoire -->
        <img src="<?= base_url('assets/img/' . $logo) ?>" alt="Logo IE" class="logo">

        <h1 class="mb-5">Bienvenue sur la plateforme IE</h1>

        <!-- Boutons vers les deux apps -->
        <a href="<?= base_url('gestion-fid') ?>" class="btn btn-primary btn-app">Accéder à Gestion FID</a>
        <a href="<?= base_url('tracker') ?>" class="btn btn-success btn-app">Accéder à Tracker</a>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-HoA+0p5N0ElGZl5HlFzYfDNQg4QktJAt1EDmR9s4Z0/5xJDONVFYfLaH1Xr1HK/2" crossorigin="anonymous"></script>
</body>
</html>
