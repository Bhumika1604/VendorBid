<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// -------------------------------------------------------------------
// Default CI4 route behaviour
// -------------------------------------------------------------------
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth\AuthController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// -------------------------------------------------------------------
// Public / Guest routes
// -------------------------------------------------------------------
$routes->get('/', 'Auth\AuthController::index');

// Contractor authentication
$routes->get('login', 'Auth\AuthController::contractorLogin');
$routes->post('login', 'Auth\AuthController::contractorLoginAction');
$routes->get('register', 'Auth\AuthController::register');
$routes->post('register', 'Auth\AuthController::registerAction');

// Admin authentication
$routes->get('admin/login', 'Auth\AuthController::adminLogin');
$routes->post('admin/login', 'Auth\AuthController::adminLoginAction');

// Logout (shared)
$routes->get('logout', 'Auth\AuthController::logout');

// -------------------------------------------------------------------
// Admin protected routes
// -------------------------------------------------------------------
$routes->group('admin', ['filter' => 'authfilter:admin'], static function (RouteCollection $routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // Project Management Module
    $routes->get('projects', 'Admin\ProjectController::index');
    $routes->get('projects/create', 'Admin\ProjectController::create');
    $routes->post('projects/store', 'Admin\ProjectController::store');
    $routes->get('projects/view/(:num)', 'Admin\ProjectController::show/$1');
    $routes->get('projects/edit/(:num)', 'Admin\ProjectController::edit/$1');
    $routes->post('projects/update/(:num)', 'Admin\ProjectController::update/$1');
    $routes->get('projects/delete/(:num)', 'Admin\ProjectController::delete/$1');

    // Bid Management Module
    $routes->get('bids', 'Admin\BidController::index');
    $routes->get('bids/view/(:num)', 'Admin\BidController::show/$1');
    $routes->get('bids/compare/(:num)', 'Admin\BidController::compare/$1');
    $routes->get('bids/download/(:num)', 'Admin\BidController::download/$1');

    // Award Management Module
    $routes->get('awards', 'Admin\AwardController::index');
    $routes->get('awards/view/(:num)', 'Admin\AwardController::show/$1');
    $routes->post('awards/store/(:num)', 'Admin\AwardController::store/$1');

    // Reports Module
    $routes->get('reports/projects', 'Admin\ReportController::projects');
    $routes->get('reports/contractors', 'Admin\ReportController::contractors');
    $routes->get('reports/bids', 'Admin\ReportController::bids');
    $routes->get('reports/awards', 'Admin\ReportController::awards');

    // Analytics (Dashboard Improvements — Chart.js)
    $routes->get('analytics', 'Admin\AnalyticsController::index');

    // Notifications
    $routes->get('notifications', 'NotificationController::index');
    $routes->get('notifications/open/(:num)', 'NotificationController::open/$1');
});

// -------------------------------------------------------------------
// Contractor protected routes
// -------------------------------------------------------------------
$routes->group('contractor', ['filter' => 'authfilter:contractor'], static function (RouteCollection $routes) {
    $routes->get('dashboard', 'Contractor\DashboardController::index');

    // Available Projects (read-only browsing for contractors)
    $routes->get('projects', 'Contractor\ProjectController::index');
    $routes->get('projects/view/(:num)', 'Contractor\ProjectController::show/$1');

    // My Profile
    $routes->get('profile', 'Contractor\ProfileController::index');
    $routes->get('profile/edit', 'Contractor\ProfileController::edit');
    $routes->post('profile/update', 'Contractor\ProfileController::update');

    // Bid Management Module
    $routes->get('bids', 'Contractor\BidController::index');
    $routes->get('bids/create', 'Contractor\BidController::create');
    $routes->post('bids/store', 'Contractor\BidController::store');
    $routes->get('bids/view/(:num)', 'Contractor\BidController::show/$1');
    $routes->get('bids/download/(:num)', 'Contractor\BidController::download/$1');

    // Notifications
    $routes->get('notifications', 'NotificationController::index');
    $routes->get('notifications/open/(:num)', 'NotificationController::open/$1');
});
