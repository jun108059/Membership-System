<?php

class Router {

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
     */
    public function add($route, $params) {
        $this->routes[$route] = $params;
    }

    /**
     * 매칭 되는 route 가 있다면 @params에 값 할당
     * @param string $url
     * @return boolean 매칭 - true, 아니면 - false
     */
    public function match($url) {
        /*
        foreach ($this->routes as $route => $params) {
            if ($url == $route) {
                $this->params = $params;
                return true;
            }
        }
        return false;
        */

        // Match to the fixed URL format /controller/action
        $reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";

        if (preg_match($reg_exp, $url, $matches)) {
            //Get named capture group values
            $params = [];

            foreach($matches as $key => $match) {
                if (is_string($key)) {
                    $params[$key] = $match;
                }
            }

            $this->params = $params;
            return true;
        }

//        return false;
    }

    // Get Routes
    public function getRoutes() {
        return $this->routes;
    }

    // Get Parameters
    public function getParams() {
        return $this->params;
    }

}