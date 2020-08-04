<?php

namespace App\Controllers;

use App\Models\Login;
use \Core\View;
use App\Service\SessionManager;
use DateTime;

class HomeController extends \Core\Controller
{
    /**
     * 메인 page 로 Rendering
     * @return boolean
     */
    public function indexAction()
    {
        session_start();

        if (!isset($_SESSION['userID']))
        {
            echo '<script> alert("🔴잘못된 접근입니다. 로그인 후 이용해주세요!🔴"); </script>';
            View::render('Login/index.php', []);
            return false;
        } elseif ((time() - strtotime($_SESSION['userLog'])) > 1800) //30분동안 활동이 없으면 자동 로그아웃
        {
            echo '<script> alert("🔴시간 초과로 로그아웃 되었습니다\n로그인 후 이용해주세요!🔴"); </script>';
            session_destroy();
            View::render('Login/index.php', []);
            return false;
        } else {
            $now = (new DateTime())->format('Y-m-d H:i:s');
            $_SESSION['userLog'] = $now;
            View::render('/Home/index.php', []);
            return true;
        }
    }

    /**
     * Home - 개인정보수정 page
     *
     * @return boolean
     */
    public function infoModifyAction() {
        session_start();

        if (!isset($_SESSION['userID']))
        {
            echo '<script> alert("🔴잘못된 접근입니다. 로그인 후 이용해주세요!🔴"); </script>';
            View::render('Login/index.php', []);
            return false;
        } elseif ((time() - strtotime($_SESSION['userLog'])) > 1800) //30분동안 활동이 없으면 자동 로그아웃
        {
            echo '<script> alert("🔴시간 초과로 로그아웃 되었습니다\n로그인 후 이용해주세요!🔴"); </script>';
            session_destroy();
            View::render('Login/index.php', []);
            return false;
        } else {
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
        return true;
    }
}