<?php

class Router {

    /**
     * 연관 배열 of routes (라우팅 테이블)
     * @var array
     */
    protected $routes = [];

    /**
     * Add a route - 라우팅 table
     * @param $route string (the route URL)
     * @param $params array (parameters - controller, action, etc)
     */

    public function add($route, $params) {
        $this->routes[$route] = $params;
    }


    public function getRoutes() {
        return $this->routes;
    }
}