<?php

namespace Core;

abstract class Controller
{

    /**
     * Parameters from the matched route
     * @var array
     */
    protected array $route_params = [];
    // 경로 parameter 저장 배열

    /**
     * Class constructor
     *
     * @param array $route_params  Parameters from the route
     *
     * @return void
     */
    // 객체를 생성할 때 불리는 생성자
    // parameter 로 경로 배열이 저장 됨
    public function __construct($route_params)
    {
        $this->route_params = $route_params;
    }
}
