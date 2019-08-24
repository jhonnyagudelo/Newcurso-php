<?php
			ini_set('display_errors', 1); //inicializa errores
			ini_set('display_starup_error', 1); //inicializa errores
			error_reporting(E_ALL);//mostrar errores en pantalla

			require_once '../vendor/autoload.php';


			session_start();

			$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..');
			$dotenv->load();


			use Illuminate\Database\Capsule\Manager as Capsule;
			use Aura\Router\RouterContainer;
			use Zend\Diactoros\Response\HtmlResponse;
			use Zend\Diactoros\Response\RedirectResponse;

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

			$map->get('index', '/curso-php/index', [
				'controller' => 'App\Controllers\IndexController',
				'action' => 'indexAction',
				'auth' => true
			]);

			$map->get('addJob', '/curso-php/jobs/agregar/add', [
				'controller' => 'App\Controllers\JobsController',
				'action' => 'getAddJobAction',
				'auth' => true
			]);

			$map->post('saveJob', '/curso-php/jobs/add', [
				'controller' => 'App\Controllers\JobsController',
				'action' => 'getAddJobAction',
				'auth' => true
			]);


			$map->get('addProject', '/curso-php/project/add',[
				'controller' => 'App\Controllers\ProjectController',
				'action' => 'getAddProjectAction',
				'auth' => true
			]);

			$map->post('saveProject', '/curso-php/project/add',[
				'controller' => 'App\Controllers\ProjectController',
				'action' => 'getAddProjectAction',
				'auth' => true
			]);

			
			$map->get('addUser', '/curso-php/user/add',[
				'controller' => 'App\Controllers\UsuarioController',
				'action' => 'postSaveUser',
				'auth' => true
			]);
				
			$map->post('saveUser', '/curso-php/user/add',[
				'controller' => 'App\Controllers\UsuarioController',
				'action' => 'postSaveUser',
				'auth' => true
			]);
				/**login */
			$map->get('loginForm', '/curso-php/Login',[
				'controller' => 'App\Controllers\AuthController',
				'action' => 'getLogin'
			]);

			$map->get('lgout', '/curso-php/Logout',[
				'controller' => 'App\Controllers\AuthController',
				'action' => 'getLogout'
				 //tiene que estar logeado
			]);

			$map->post('auth', '/curso-php/auth',[
				'controller' => 'App\Controllers\AuthController',
				'action' => 'postLogin'
			]);

			$map->get('admin', '/curso-php/admin',[
				'controller' => 'App\Controllers\AdminController',
				'action' => 'getIndex',
				'auth' => true //tiene que estar logeado
			]);



			$matcher = $routerContainer->getMatcher();
			$route = $matcher->match($request);


			if(!$route) {
				 $response = new HtmlResponse('No route found!', 404);
				exit;
			} else {
				$handlerData = $route->handler;
				$controllerName = $handlerData['controller']; //va a instanciar el contenido de la variable
				$actionName = $handlerData['action'];
				$needsAuth = $handlerData['auth'] ?? false; // autentificacion 

				$sessionUserId = $_SESSION['userId'] ?? null;
				if($needsAuth && !$sessionUserId){
					$response = new RedirectResponse('/curso-php/Login');
				}

				$controller = new $controllerName();
				$response = $controller->$actionName($request);
				
				foreach ($response->getHeaders() as $name => $values) {
					foreach ($values as $value) {
						header(sprintf('%s: %s', $name, $value), false);
					}	
				}
				http_response_code($response->getStatusCode()); //codigo de respunesta que vamos a enviar 
				echo $response->getBody();

			}



	function printElement($job){
		echo '<li class="work-position">';
		echo '<h5>' . $job->title .'</h5>';
		echo '<p>'. $job->description .'</p>';
		echo '<p>'. $job->getDurationAsString() .'</p>';
		echo '<strong>Achievements:</strong>';
		echo '<ul>';
		echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
		echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
		echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
		echo '</ul>';
		echo '</li>';
	}



	function printProjects($project){
		  echo '<div class="project">';
		  echo '<h5>'.$project->title_project.'</h5>';
		  echo '<div class="row">';
		  echo '<div class="col-3">';
		  echo '<img id="profile-picture" src="https://ui-avatars.com/api/?name=John+Doe&size=255" alt="">';
		  echo '</div>';
		  echo '<div class="col">';
		  echo '<p>'.$project->description.'</p>';
		  echo '<strong>Technologies used:</strong>';
		  echo '<p>'.$project->technologies.'</p>';

		  echo '</div>';
		  echo '</div>';
		  echo '</div>';
	}
				// var $route->handler;
			// var_dump($route->handler);
			// var_dump($request->getUri()->getPath()); //definido como parte del psr
