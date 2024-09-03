<?php

use App\controllers\adminControllers;
use App\controllers\connexionControllers;
use App\controllers\hostControllers;
use App\controllers\prepaControllers;
use App\controllers\testControllers;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\HostMiddleware;
use App\Middlewares\PrepaMiddleware;
use App\controllers\apiControllers;
use App\Utils\router;

require "Utils/init.php";

define("APPLICATION_PATH", __DIR__."/templates/pages/"); // Chemin vers les templates de pages

$router = new router();
// Route pour le formulaire de connexion
$router->add(['GET'], '/', [connexionControllers::class, 'displayConnexionForm']);
$router->add(['GET','POST'], '/connexion', [connexionControllers::class, 'displayUserView']);
$router->add(['GET'], '/deconnexion', [connexionControllers::class, 'deconnectUser']);
// Routes pour le rôle ADMIN
$router->add(['GET'], '/admin', [adminControllers::class, 'displayAdminHomePage'],[AdminMiddleware::class]);
$router->add(['GET'], '/admin/products', [adminControllers::class, 'displayAdminProductsList'],[AdminMiddleware::class]);
$router->add(['GET'], '/admin/products/add', [adminControllers::class, 'displayAdminProductAddForm'],[AdminMiddleware::class]);
$router->add(['POST'], '/admin/products/save', [adminControllers::class, 'saveProduct'],[AdminMiddleware::class]);
$router->add(['GET'], '/admin/products/modif/{id}', [adminControllers::class, 'displayAdminProductModifForm'],[AdminMiddleware::class]);
$router->add(['GET','POST'], '/admin/products/update/{id}', [adminControllers::class, 'updateProduct'],[AdminMiddleware::class]);
$router->add(['GET'], '/admin/products/delete/{id}', [adminControllers::class, 'deleteProduct'],[AdminMiddleware::class]);
$router->add(['GET'], '/admin/users', [adminControllers::class, 'displayAdminUsersList'],[AdminMiddleware::class]);
$router->add(['GET'], '/admin/users/add', [adminControllers::class, 'displayAdminUserAddForm'],[AdminMiddleware::class]);
$router->add(['POST'], '/admin/users/save', [adminControllers::class, 'saveUser'],[AdminMiddleware::class]);
$router->add(['GET'], '/admin/users/modif/{id}', [adminControllers::class, 'displayAdminUserModifForm'],[AdminMiddleware::class]);
$router->add(['GET', 'POST'], '/admin/users/update/{id}', [adminControllers::class, 'updateUser'],[AdminMiddleware::class]);
$router->add(['GET'], '/admin/users/delete/{id}', [adminControllers::class, 'deleteUser'],[AdminMiddleware::class]);
// Routes pour le rôle PREPARATEUR
$router->add(['GET'], '/prepa', [prepaControllers::class, 'displayPrepaOrdersList'],[PrepaMiddleware::class]);
$router->add(['GET'], '/prepa/orders/ready/{id}', [prepaControllers::class, 'changePrepaOrderStatus'],[PrepaMiddleware::class]);
$router->add(['GET'], '/prepa/orders/details/{id}', [prepaControllers::class, 'extractOrderDetails'],[PrepaMiddleware::class]);
// Routes pour le rôle HOTE
$router->add(['GET'], '/host', [hostControllers::class, 'displayHostOrdersList'],[HostMiddleware::class]);
$router->add(['GET'], '/host/orders/ready/{id}', [hostControllers::class, 'changeHostOrderStatus'],[HostMiddleware::class]);
$router->add(['GET'], '/host/orders/details/{id}', [hostControllers::class, 'extractOrderDetails'],[HostMiddleware::class]);
$router->add(['GET'], '/host/orders/add', [hostControllers::class, 'displayHostOrderView'],[HostMiddleware::class]);
// APIs
$router->add(['GET','POST','OPTIONS'], '/api/add-order', [apiControllers::class, 'addOrder']);
$router->add(['GET','OPTIONS'], '/api/get-products', [apiControllers::class, 'listProducts']);
$router->add(['GET','OPTIONS'], '/api/get-categories', [apiControllers::class, 'listCategories']);
$router->add(['GET','OPTIONS'], '/api/prepare-order', [apiControllers::class, 'prepareOrder']);

// Route pour les controllers de test
//$router->add(['GET'], '/test', [testControllers::class, 'test']);


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($path);