<?php

use app\controllers\SiteController;
use app\core\Application;
use app\controllers\Auth;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


$config = [
    'userClass' => \app\models\User::class,
    'db' => [
        'host' => $_ENV['DB_HOST'],
        'user' => $_ENV['DB_USERNAME'],
        'pass' => $_ENV['DB_PASSWORD'],
    ]
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/contact', [SiteController::class , 'contact']);
$app->router->post('/contact', [SiteController::class , 'action']);

$app->router->get('/login', [Auth\AuthController::class , 'login']);
$app->router->post('/login', [Auth\AuthController::class , 'login']);
$app->router->get('/register', [Auth\AuthController::class , 'register']);
$app->router->post('/register', [Auth\AuthController::class , 'register']);
$app->router->get('/logout', [Auth\AuthController::class , 'logout']);
$app->run();
