<?php

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

}