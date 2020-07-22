<?php

namespace App\Controllers;

use \Core\View;

class Home extends \Core\Controller
{
    // 메인 코어 Controller 를 상속 받음

    /**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        echo "(before) ";
        // return false;
        // false 를 리턴 하면 메소드 실행 안 됨!
        // 활용 하면 login 기능 구현 쉬움

//        if ( ! isset($_SESSION["user_id"])) {
//            return false;
//        }

    }

    /**
     * After filter
     * @return void
     */
    protected function after()
    {
        echo " (after)";
    }

    /**
     * Show the index page
     * @return void
     */
    public function indexAction()
    {
        // index action in the Home controller
        // View 렌더링 추가!
        View::render('Home/index.php', [
            'name'    => 'Dave',
            'colours' => ['red', 'green', 'blue']
        ]);

    }


}