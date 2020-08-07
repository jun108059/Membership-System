<?php

namespace App\Controllers;

use App\Models\Login;
use \Core\View;
use DateTime;

class HomeController extends \Core\Controller
{
    /**
     * 메인 page 로 Rendering
     */
    public function indexAction()
    {
        if (empty($_SESSION['userID'])) {
            echo '<script> alert("🔴잘못된 접근입니다. 로그인 후 이용해주세요!🔴"); </script>';
            View::render('Login/index.php');
        }else {
            $now = (new DateTime())->format('Y-m-d H:i:s');
            $_SESSION['userLog'] = $now;
            View::render('/Home/index.php');
        }
    }

    /**
     * Home - 개인정보수정 page
     */
    public function infoModifyAction()
    {
        if (empty($_SESSION['userID'])) {
            echo '<script> alert("🔴잘못된 접근입니다. 로그인 후 이용해주세요!🔴"); </script>';
            View::render('Login/index.php');
        }else {
            $now = (new DateTime())->format('Y-m-d H:i:s');
            $_SESSION['userLog'] = $now;
        }

        $user = Login::getUserData($_SESSION['userID']);
        View::render('Home/infoModify.php', [
            'user_id' => $user['mem_user_id'],
            'user_pw' => $user['mem_password'],
            'user_name' => $user['mem_name'],
            'phone' => $user['mem_phone'],
            'gender' => $user['mem_gender'],
            'register' => $user['mem_reg_dt'],
            'email' => $user['mem_email']
        ]);
    }
}