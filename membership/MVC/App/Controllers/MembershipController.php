<?php

namespace App\Controllers;

use App\Models\Membership;
use \Core\View;

class MembershipController extends \Core\Controller
{
    public function signUpAction()
    {
        // View 페이지 렌더링 해주기
        View::render('Membership/signUp.php',);
    }

    public function signUpDBAction()
    {
        if(empty( $_POST['mem_id']) || empty( $_POST['password'])){
            exit;
        }


        $userData = [
            'user_id' => $_POST['mem_id'],
            'password' =>'',
            'state' => 'y'
        ];

        // 가입 완료 버튼 -> DB data 넣기
        // 모델 에서 데이터 꺼내 오기
        $id = Membership::getId();
        $insert = Membership::insertInfo($userData);

        View::render('Membership/signUpOK.php', [
        ]);

        // index action in the controller
        // View 렌더링 추가!
        /*
        View::render('Home/index.php', [
            'name'    => 'Dave',
            'colours' => ['red', 'green', 'blue']
        ]);
        */
    }
    public function certificationAction()
    {
        /** 세션 정보를 바로 유지시키는게 좋을 듯!*/

        // 가입 완료 버튼 -> DB data 넣기
        // 모델 에서 데이터 꺼내 오기
        $id = Membership::getId();
        View::render('Membership/certificate.php', [
            'user' => $_POST["mem_user_id"]
        ]);

        // index action in the controller
        // View 렌더링 추가!
        /*
        View::render('Home/index.php', [
            'name'    => 'Dave',
            'colours' => ['red', 'green', 'blue']
        ]);
        */
    }
    /** Before or After filter 추가 가능 */


}