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
     * @return void
     */
    public function indexAction()
    {
//        session_start();
        $session_manager = new SessionManager();
        print_r($_SESSION);
//        exit();
        //유효한 접근이 아니거나 로그인 유효시간이 지나면 로그인 page 로 이동
        if (!$session_manager->isValidAccess() || $session_manager->isLoginExpired()) {
            $session_manager->destroy_session();
            echo '<script> alert("🔴잘못된 접근입니다. 로그인 후 이용해주세요!🔴"); </script>';
            View::render('Login/index.php', []);
        } // 로그인 유효 시간 갱신
        else{
            $session_manager->update_active_time();
            View::render('Home/index.php', [
                'session' => $session_manager
            ]);
        }
    }

    public function myNameAction()
    {
        View::render('Home/myname.php', []);
    }

    /**
     *
     */
    public function infoModifyAction() {
        session_start();
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $_SESSION['userLog'] = $now;
//        $session_manager = new SessionManager();
//        print_r($_SESSION);
//        if (!$session_manager->isValidAccess() || $session_manager->isLoginExpired()) {
//            $session_manager->destroy_session();
//            echo '<script> alert("🔴잘못된 접근입니다. 로그인 후 이용해주세요!🔴"); </script>';
//            View::render('Login/index.php', []);
//        } // 로그인 유효 시간 갱신
//        else{
//            $session_manager->update_active_time();
//            // 유저 정보 get
//        }
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