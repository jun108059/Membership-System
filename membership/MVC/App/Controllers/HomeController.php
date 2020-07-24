<?php

namespace App\Controllers;

use \Core\View;

class HomeController extends \Core\Controller
{
    // 메인 코어 Controller 를 상속 받음

    /**
     * Show the index page
     * @return void
     */
    public function indexAction()
    {
        // index action in the HomeController
        // View 렌더링 추가!

        View::render('Home/index.php', [
            'name'    => 'Dave',
            'colours' => ['red', 'green', 'blue']
        ]);

        View::renderTemplate('Home/index.php', [
            'name'    => 'YoungJun',
            'colours' => ['red', 'green', 'blue']
        ]);
    }


}