<?php

namespace App\Controllers;

use \Core\View;

class Posts extends \Core\Controller
{

    /**
     * index action 함수 정의
     * 검색 기능
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Posts/index.html');
    }

    /**
     * add New 함수 정의
     * 새로운 게시글 작성 기능
     * @return void
     */
    public function addNewAction()
    {
        echo 'Hello from the addNew action in the Posts controller!';
    }

    /**
     * Show the edit page
     *
     * @return void
     */
    public function editAction()
    {
        echo 'Hello from the edit action in the Posts controller!';
        echo '<p>Route parameters: <pre>' .
            htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
    }
}
