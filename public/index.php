<?php
 
use App\Core\Router;
use App\Core\Database;
 
const BASE_PATH = __DIR__ . "/../";
 
require str_replace('/', DIRECTORY_SEPARATOR, BASE_PATH  . 'App/Core/functions.php');
 
// // Extracts the URL path without the query string
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
 
spl_autoload_register(function($class){
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require base_path("$class.php");
});
  
$router = new Router();
$routes = require base_path('App/Core/routes.php');
$router->route($path, $method);