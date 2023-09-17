<?php

require_once __DIR__ . '/../vendor/autoload.php';
use app\controllers\SiteController;
use app\core\Application;
use app\controllers\Auth;

$app = new Application(dirname(__DIR__));

$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/contact', [SiteController::class , 'contact']);
$app->router->post('/contact', [SiteController::class , 'action']);

$app->router->get('/login', [Auth\AuthController::class , 'login']);
$app->router->post('/login', [Auth\AuthController::class , 'login']);
$app->router->get('/register', [Auth\AuthController::class , 'register']);
$app->router->post('/register', [Auth\AuthController::class , 'register']);
$app->run();
