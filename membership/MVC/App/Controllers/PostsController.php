<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Post;

/**
 * PostsController
 *
 * PHP version 7.4
 */
class PostsController extends \Core\Controller
{

    /**
     * index action 함수 정의
     * 검색 기능
     * @return void
     */
    public function indexAction()
    {
        // 모델 에서 data 꺼내 오기
        $posts = Post::getAll();
        View::renderTemplate('Posts/index.html', [
            // renderTemplate 에서 변수 추출해 줌
            'posts' => $posts
        ]);
    }

    /**
     * add New 함수 정의
     * 새로운 게시글 작성 기능
     * @return void
     */
    public function addNewAction()
    {
        echo 'Hello from the addNew action in the PostsController';
    }

    /**
     * Show the edit page
     *
     * @return void
     */
    public function editAction()
    {
        echo 'Hello from the edit action in the PostsController';
        echo '<p>Route parameters: <pre>' .
            htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
    }
}
