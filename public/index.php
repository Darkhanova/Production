<?php
require_once "../vendor/autoload.php";
require_once "../framework/autoload.php";
require_once "../controllers/MainController.php";
require_once "../controllers/ObjectController.php";
require_once "../controllers/Controller404.php";
require_once '../controllers/SearchController.php';
require_once '../controllers/CatDeleteController.php';
require_once '../controllers/CatCreateController.php';
require_once '../controllers/CatUpdateController.php';
require_once '../controllers/CatTypeCreateController.php';
require_once '../middlewares/LoginRequiredMiddleware.php';

$loader = new \Twig\Loader\FilesystemLoader('../views');

$twig = new \Twig\Environment($loader, [
    "debug" => true // добавляем тут debug режим
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$context = [];

$pdo = new PDO("mysql:host=localhost;dbname=cats;charset=utf8", "root", "");

$router = new Router($twig, $pdo);
$router->add("/", MainController::class);
$router->add("/cats_objects/(?P<id>\d+)(\?show=(?P<section>\w+))?", ObjectController::class);
$router->add("/search", SearchController::class);

$router->add("/cats_objects/create", CatCreateController::class)
    ->middleware(new LoginRequiredMiddleware());
$router->add("/create_type", CatTypeCreateController::class)
    ->middleware(new LoginRequiredMiddleware());
$router->add("/cats_objects/(?P<id>\d+)/delete", CatDeleteController::class)
    ->middleware(new LoginRequiredMiddleware());
$router->add("/cats_objects/(?P<id>\d+)/update", CatUpdateController::class)
    ->middleware(new LoginRequiredMiddleware());

$router->get_or_default(Controller404::class);
