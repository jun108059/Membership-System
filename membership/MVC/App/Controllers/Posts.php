<?php

namespace App\Controllers;

class Posts
{

    /**
     * index action 함수 정의
     * 검색 기능
     * @return void
     */
    public function index()
    {
        echo 'Hello from the index action in the Posts controller!';
    }

    /**
     * add New 함수 정의
     * 새로운 게시글 작성 기능
     * @return void
     */
    public function addNew()
    {
        echo 'Hello from the addNew action in the Posts controller!';
    }
}
