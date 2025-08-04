<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme Groupe IE</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Thème CSS personnalisé -->
    <link href="/assets/css/theme.css" rel="stylesheet">
</head>
<body class="d-flex flex-column justify-content-center align-items-center min-vh-100">

    <!-- Logo aléatoire -->
    <?php
       $logos = [base_url('assets/img/logo-ie-couleur.png'), base_url('assets/img/logo-ie-noir.png')];

        $logo = $logos[array_rand($logos)];
    ?>
    <img src="<?= $logo ?>" alt="Logo Groupe IE" class="logo">

    <!-- Titre principal -->
    <h1 class="mb-4">Bienvenue sur la plateforme Groupe IE</h1>

    <!-- Boutons d'accès aux applications -->
    <div class="d-flex gap-4">
        <a href="/gestion-fid" class="btn btn-ie-fid btn-lg">Gestion FID </a>
        <a href="/tracker" class="btn btn-ie-tracker btn-lg">Tracker</a>
    </div>

    <!-- Footer -->
    <div class="footer mt-5">
        &copy; Groupe IE - Tous droits réservés
    </div>

</body>
</html>