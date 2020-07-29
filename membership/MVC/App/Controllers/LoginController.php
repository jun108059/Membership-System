<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Login;
use SessionService;

class LoginController extends \Core\Controller
{
    public function loginCheckAction()
    {
        // 모델 에서 데이터 꺼내 오기
        $user = Login::getIdPassword();

        // 각 변수에 ID, PW 저장
        $user_id = $_POST['user_id'];
        $user_pw = $_POST['user_password'];

        if (empty($user_id) || empty($user_pw)) { // empty 로 빈값 체크
            echo '<script> alert(" ❓아이디 또는 패스워드 입력하세요❓"); history.back(); </script>';
        } else {
            // pw_check 변수에 Login Model 저장 password 받아 오기
            $pw_check = Login::getPassword($user_id);

            //만약 password 와 hash_pw 가 같다면 세션 값을 저장
            //알림창 띄운후 Home rendering
            if (password_verify($user_pw, $pw_check['mem_password'])) {
                $session_manager = new SessionService();
//                session_start();
                $_SESSION['mem_user_id'] = $user['mem_user_id'];
                $_SESSION['mem_password'] = $user['mem_password'];

                echo "<script>alert('✔ 로그인 되었습니다 ✔'); location.href='/home/index';</script>";
            } else { // 비밀번호가 같지 않다면 알림창을 띄우고 전 페이지로 돌아갑니다
                echo "<script>alert('❗ 아이디 또는 비밀번호를 확인하세요 ❗'); history.back();</script>";
            }
        }
        /** Render 를 Home 으로 해줘야 되면 쓰기
         * 내 생각엔 그냥 location 으로 url 줘도 될 듯 (검사 했기 때문에)
         * View::render('Login/loginCheck.php', [
         * 'user_id' => $_POST["mem_user_id"],
         * 'user_pw' => $_POST["mem_password"]
         * ]);
         */

    }

    protected function before()
    {
        if (isset($_SESSION['mem_user_id'])) {
            View::render('Home/index.html', []);
        }
    }

    /**
     * Show the index page
     * @return void
     */
    public function indexAction()
    {
        View::render('Login/index.php', []);
    }


}