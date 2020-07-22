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

    /**
     * non-existent or inaccessible method 일 때 Magic method 호출
     * 해당 함수 호출 전 before and after 코드 작성 가능
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param string $name  Method name
     * @param array $args Arguments passed to the method
     *
     * @return void
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action'; // 메소드 이름 + Action (Suffix)

        if (method_exists($this, $method)) { // 메소드 존재 하면
            if ($this->before() !== false) { // before 메소드 호출
                call_user_func_array([$this, $method], $args); // 해당 메소드 실행
                $this->after(); // after 메소드 호출
            }
        } else {
            echo "Method $method not found in controller " . get_class($this);
        }
    }

    /**
     * Before filter - called before an action method.
     *
     * @return void
     */
    protected function before()
    {
    }

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after()
    {
    }
}
