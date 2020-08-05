<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Login;
use DateTime;

class LoginController extends \Core\Controller
{

    /**
     * 메인 페이지
     * @return void
     */
    public function indexAction()
    {
        session_start();
        if(isset($_SESSION['userID'])) {
            if ($_SESSION['userID'] === 'admin' && $_SESSION['userLevel'] === '1')
                View::render('Admin/loginOK.html', []);
            else
                View::render('Login/loginOK.html', []);
        }else {
            View::render('Login/index.php', []);
        }
    }

    /**
     * Login 검사
     */
    public function loginCheckAction()
    {


        // 각 변수에 ID, PW 저장
        $user_id = $_POST['user_id'];
        $user_pw = $_POST['user_password'];

        // View & Back-end 이중 체크
        if (empty($user_id) || empty($user_pw)) { // empty 로 빈값 체크
            echo '<script> alert(" ❓아이디 또는 패스워드 입력하세요❓"); history.back(); </script>';
        }

        // 모델 에서 데이터 꺼내 오기
        $user = Login::getUserData($user_id);
        $pw_check = $user['mem_password'];
//        $user_log = $user['mem_log_dt'];
        $status_check = $user['mem_status']; // 계정 상태 확인


        //만약 password 와 hash_pw 가 같다면 세션 실행
        if (password_verify($user_pw, $pw_check)) {
            session_start();
            if ($status_check === 'H') {
                echo '<script> alert("고객님은 휴면계정입니다!🔒"); </script>';
                $_SESSION["userID"] = $user_id;
                $_SESSION["userEmail"] = $user['mem_email'];
                View::render('Login/dormant.php', [
                    'user_id' =>  $_SESSION["userID"],
                    'user_email' =>  $_SESSION["userEmail"]
                ]);
                exit();
            }

            $_SESSION["userID"] = $user_id;
            $_SESSION["userLog"] = (new DateTime())->format('Y-m-d H:i:s');
            $_SESSION["userLevel"] = $user['mem_level'];
            if ($_SESSION["userLevel"] === '1') {
                View::render('Admin/loginOK.html', []);
            }else {
                View::render('Login/loginOK.html', []);
            }
        } else { // 비밀번호가 같지 않다면 알림창을 띄우고 전 페이지로 돌아갑니다
            echo "<script>alert('❗ 아이디 또는 비밀번호를 확인하세요 ❗'); history.back();</script>";
        }
        /** Render 를 Home 으로 해줘야 되면 쓰기
         * 내 생각엔 그냥 location 으로 url 줘도 될 듯 (검사 했기 때문에)
         * View::render('Login/loginCheck.php', [
         * 'user_id' => $_POST["mem_user_id"],
         * 'user_pw' => $_POST["mem_password"]
         * ]);
         */

    }

    /**
     * 로그아웃 -> session 제거 & View Render
     */
    public function logoutAction()
    {
        session_start();
        session_destroy();
        View::render('Login/logout.html', []);
    }

    protected function before()
    {
//        if (isset($_SESSION['mem_user_id'])) {
//            View::render('Home/index.html', []);
//        }
    }


}
