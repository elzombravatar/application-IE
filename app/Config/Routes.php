<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route par défaut (déjà existante)
$routes->get('/', 'Home::index');

// === ROUTES FID ===
$routes->group('fid', function($routes) {
    // Pages principales
    $routes->get('/', 'FidController::index');                    // Liste des FID
    $routes->get('create', 'FidController::create');              // Formulaire création
    $routes->get('show/(:num)', 'FidController::show/$1');        // Consultation FID
    $routes->get('edit/(:num)', 'FidController::edit/$1');        // Formulaire modification
    
    // API AJAX
    $routes->post('store', 'FidController::store');               // Créer FID
    $routes->post('update/(:num)', 'FidController::update/$1');   // Modifier FID
    $routes->delete('delete/(:num)', 'FidController::delete/$1'); // Supprimer FID
});

// === ROUTES CLIENT (Formulaire externe) ===
$routes->group('client', function($routes) {
    $routes->get('form/(:any)', 'ClientController::form/$1');     // Formulaire client avec token
    $routes->post('submit/(:any)', 'ClientController::submit/$1'); // Soumission client
    $routes->get('success', 'ClientController::success');         // Page de confirmation
});

// === ROUTES API ===
$routes->group('api', function($routes) {
    // API SIRENE pour auto-complétion
    $routes->get('sirene/(:num)', 'ApiController::sirene/$1');
    
    // API référentiels (pour AJAX)
    $routes->get('integrites', 'ApiController::integrites');
    $routes->get('codes-un', 'ApiController::codesUn');
    $routes->get('types-conditionnement', 'ApiController::typesConditionnement');
    $routes->get('codes-dechets', 'ApiController::codesDechets');
});

// === ROUTES EXPORT ===
$routes->group('export', function($routes) {
    $routes->get('fid/(:num)', 'ExportController::fidToExcel/$1');    // Export Excel
    $routes->get('fid/(:num)/pdf', 'ExportController::fidToPdf/$1');  // Export PDF
});

// === ROUTES EMAIL ===
$routes->group('email', function($routes) {
    $routes->post('send-to-client/(:num)', 'EmailController::sendToClient/$1'); // Envoyer formulaire au client
    $routes->get('preview/(:num)', 'EmailController::preview/$1');              // Prévisualiser email
});

// === ROUTES AUTHENTIFICATION (Future) ===
$routes->group('auth', function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::attemptRegister');
});

// === ROUTES TRACKDÉCHETS (Phase 2) ===
$routes->group('trackdechets', function($routes) {
    $routes->get('/', 'TrackdechetsController::index');                    // Dashboard PWA
    $routes->get('bordereaux', 'TrackdechetsController::bordereaux');      // Liste bordereaux
    $routes->post('webhook', 'TrackdechetsController::webhook');           // Réception webhooks
    $routes->get('notifications', 'TrackdechetsController::notifications'); // Gestion notifications
});

// === ROUTE DE TEST (Temporaire) ===
$routes->get('test', function() {
    echo '<h1>Test IE-TRANS Platform</h1>';
    echo '<p>Routes configurées !</p>';
    echo '<ul>';
    echo '<li><a href="/fid">Gestion des FID</a></li>';
    echo '<li><a href="/fid/create">Créer une FID</a></li>';
    echo '<li><a href="/api/integrites">API Intégrités (JSON)</a></li>';
    echo '</ul>';
});

$routes->get('test-url', function() {
    return view('test_base_url');
});