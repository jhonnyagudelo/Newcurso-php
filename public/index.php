<?php
ini_set('display_errors', 1); //inicializa errores
ini_set('display_starup_error', 1); //inicializa errores
error_reporting(E_ALL); //mostrar errores en pantalla

require_once '../vendor/autoload.php';


session_start();

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..');
$dotenv->load();




use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response;
use WoohooLabs\Harmony\Harmony;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;

/**Contenedor de dependencias */
$container = new DI\Container();


$capsule = new Capsule;

$capsule->addConnection([
	'driver'    => getenv('DB_DRIVER'),
	'host'      => getenv('DB_HOST'),
	'port'      => getenv('DB_PORT'),
	'database'  => getenv('DB_NAME'),
	'username'  => getenv('DB_USER'),
	'password'  => getenv('DB_PASS'),
	'charset'   => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

//password
password_hash('superSecurePaswd', PASSWORD_DEFAULT);


$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
	$_SERVER,
	$_GET,
	$_POST,
	$_COOKIE,
	$_FILES
);


$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

$map->get('index', '/curso-php/', [
	'App\Controllers\IndexController',
	'indexAction',
	// 'auth' => true
]);

$map->get('addJob', '/curso-php/jobs/agregar/add', [
	'App\Controllers\JobsController',
	'getAddJobAction',
	// 'auth' => true
]);


$map->get('indexJob', '/curso-php/jobs', [
	'App\Controllers\JobsController',
	'indexAction',
	'auth' => true
]);


$map->get('deleteJob', '/curso-php/delete', [
	'App\Controllers\JobsController',
	'deleteAction',
	// 'auth' => true
]);


$map->get('indexProject', '/curso-php/projects', [
	'App\Controllers\ProjectController',
	'indexActionProject',
	'auth' => true
]);


$map->get('deleteProject', '/curso-php/deleteProjects', [
	'App\Controllers\projectController',
	'deleteAction',
	// 'auth' => true
]);


$map->post('saveJob', '/curso-php/jobs/add', [
	'App\Controllers\JobsController',
	'getAddJobAction',
	// 'auth' => true
]);


$map->get('addProject', '/curso-php/project/add', [
	'App\Controllers\ProjectController',
	'getAddProjectAction',
	// 'auth' => true
]);

$map->post('saveProject', '/curso-php/project/add', [
	'App\Controllers\ProjectController',
	'getAddProjectAction',
	// 'auth' => true
]);


$map->get('addUser', '/curso-php/user/add', [
	'App\Controllers\UsuarioController',
	'postSaveUser',
	// 'auth' => true
]);

$map->post('saveUser', '/curso-php/user/add', [
	'App\Controllers\UsuarioController',
	'postSaveUser',
	// 'auth' => true
]);
/**login */
$map->get('loginForm', '/curso-php/Login', [
	'App\Controllers\AuthController',
	'getLogin'
]);

$map->get('lgout', '/curso-php/Logout', [
	'App\Controllers\AuthController',
	'getLogout'
	//tiene que estar logeado
]);

$map->post('auth', '/curso-php/auth', [
	'App\Controllers\AuthController',
	'postLogin'
]);

$map->get('admin', '/curso-php/admin', [
	'App\Controllers\AdminController',
	'getIndex',
	// 'auth' => true //tiene que estar logeado
]);



$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);


if (!$route) {
	$response = new HtmlResponse('No route found!', 404);
	exit;
} else {
	// $handlerData = $route->handler;
	// $controllerName = $handlerData['controller']; //va a instanciar el contenido de la variable
	// $actionName = $handlerData['action'];
	// $needsAuth = $handlerData['auth'] ?? false; // autentificacion 

	// $sessionUserId = $_SESSION['userId'] ?? null;

	// if ($needsAuth && !$sessionUserId) {
	// 	$controllerName = 'App\Controllers\AuthController';
	// 	$actionName = 'getLogin';
	// 	$_SESSION['mensaje'] = 'Ruta protegida';
		// $response = new RedirectResponse('/curso-php/Login');
	//}

	// $controller = new $controllerName();


	/**Harmony */
	$harmony = new Harmony($request, new Response());
	$harmony
		->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter()))
		->addMiddleware(new Middlewares\AuraRouter($routerContainer))
		->addMiddleware(new DispatcherMiddleware($container,'request-handler'))
		->run();


	// $controller = $container->get($controllerName);
	// $response = $controller->$actionName($request);


/**
 * ESTO ES PARA LEER LOS HEADER Y ENVIARLOS A IMPRIMIR, 
 * obtener emcabezados   
 */
	// foreach ($response->getHeaders() as $name => $values) {
	// 	foreach ($values as $value) {
	// 		header(sprintf('%s: %s', $name, $value), false);
	// 	}
	// }
	// http_response_code($response->getStatusCode()); //codigo de respunesta que vamos a enviar 
	// echo $response->getBody();

}



				// var $route->handler;
			// var_dump($route->handler);
			// var_dump($request->getUri()->getPath()); //definido como parte del psr
