<?php

namespace Core;

use Exception;

class Router
{

    /**
     * @routes 연관 배열 of routes (라우팅 테이블)
     * @params parameters from 매칭된 route
     * @var array
     */
    protected array $routes = [];
    protected array $params = [];

    /**
     * Add a route - 라우팅 table
     * @param $route string (the route URL)
     * @param $params array (parameters - controller, action, etc)
     *
     * @return void
     */
    public function add($route, $params = [])
    {
        // 주소를 정규 표현 식으로 변환 ('/'앞에 '\' 추가)
        // {controller} / {action} -> {controller} \/ {action}
        $route = preg_replace('/\//', '\\/', $route);

        // 변수 바꾸기
        // {controller} \/ {action} -> {?P<controller>[a-z-]+) \/ (?P<action>[a-z-]+)
        $route = preg_replace('/{([a-z]+)}/', '(?P<\1>[a-z-]+)', $route);

        // 변수 변환 with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/{([a-z]+):([^}]+)}/', '(?P<\1>\2)', $route);

        // 맨앞, 맨뒤 추가
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;

    }

    /**
     * 매칭 되는 route 가 있다면 @params에 값 할당
     * @param string $url
     * @return boolean 매칭 - true, 아니면 - false
     */
    public function match($url)
    {

        // Match to the fixed URL format /controller/action
        //$reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                //Get named capture group values
                // $params = [];

                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    // Get Routes
    public function getRoutes()
    {
        return $this->routes;
    }

    // Get Parameters
    public function getParams()
    {
        return $this->params;
    }

    /** dispatch 함수
     * controller object 생성 -> action method 실행
     * @param $url
     * @throws Exception
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url); // query 제거

        if ($this->match($url)) { // 라우팅 table 과 일치 하는 경우
            $controller = $this->params['controller'];
            // class naming 컨벤션 - Studly Caps
            $controller = $this->convertToStudlyCaps($controller);
            // namespace 추가
            $controller = "App\Controllers\\$controller";

            if (class_exists($controller)) { // class 가 존재 하는 경우
                $controller_object = new $controller($this->params); // Core\Controller 에서 선언한 생성자

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);
                // action 값을 받아서 Camel Case 로 변환

                // Error & 잠재적 보안 핸들링 - action 필터 패턴 매칭
                if (preg_match('/action$/i', $action) == 0) {
                    // 메소드 직접 접근 제어
                    $controller_object->$action();
                } else {
                    throw new Exception("Method $action in controller $controller cannot be called directly - remove the Action suffix to call this method");
                }
            } else { // class 가 존재 하지 않는 경우 not found 출력
                echo "Controller class $controller not found";
            }
        } else {
            echo 'No route matched.';
        }
    }

    /**
     * Convert the string with hyphens to StudlyCaps,
     * e.g. post-authors => PostAuthors
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert the string with hyphens to camelCase,
     * e.g. add-new => addNew
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table. For
     * example:
     *
     *   URL                           $_SERVER['QUERY_STRING']  Route
     *   -------------------------------------------------------------------
     *   localhost                     ''                        ''
     *   localhost/?                   ''                        ''
     *   localhost/?page=1             page=1                    ''
     *   localhost/posts?page=1        posts&page=1              posts
     *   localhost/posts/index         posts/index               posts/index
     *   localhost/posts/index?page=1  posts/index&page=1        posts/index
     *
     * A URL of the format localhost/?page (one variable name, no value) won't
     * work however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed through to the $_SERVER variable).
     *
     * @param string $url The full URL
     *
     * @return string The URL with the query string variables removed
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }
}

