<?php
/**
 * Front Controller
 */

// echo 'Hello from the public folder!"'.$_SERVER['QUERY_STRING'].'"';

/* namespace + autoload 활용 -> 코드 삭제
// Controller class
require_once '../App/Controllers/Posts.php';
// Routing
require_once '../Core/Router.php';
*/

spl_autoload_register(function ($class){
    $root = dirname(__DIR__); // 부모 directory 저장
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_readable($file)) {
        require $root . '/' . str_replace('\\', '/', $class) . '.php';
    }
});

$router = new Core\Router(); // namespace 적용

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{action}/{controller}');

/* dispatch 함수를 통해 일치 여부 검사 (삭제)
// Display the routing table
echo '<pre>';
//var_dump($router->getRoutes());
echo htmlspecialchars(print_r($router->getRoutes(), true));
echo '</pre>';

// Match the requested route
$url = $_SERVER['QUERY_STRING'];

if ($router->match($url)) {
    echo '<pre>';
    var_dump($router->getParams());
    echo '</pre>';
} else {
    echo "No route found for URL '$url'";
}
*/

$router->dispatch($_SERVER['QUERY_STRING']);

/*************라우팅 vs dispatch************/
// 1. routing : asking for directions
// 2. dispatching : following those directions

// controller object 생성 -> action method 실행
// 클래스 - StudlyCaps (PSR1)
// 메소드 - camelCase
