<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Admin;
use DateTime;


class AdminController extends \Core\Controller
{

    /**
     * 메인 페이지
     * @return void
     */
    public function indexAction()
    {
        session_start();
        if(!isset($_SESSION['userID']) || $_SESSION['userLevel'] !== '1') {
            echo '<script> alert("🧨잘못된 접근입니다."); history.back(); </script>';
//    echo "<meta http-equiv='refresh' content='0; url=/'>";
            exit;
        }
        $_SESSION["userLog"] = (new DateTime())->format('Y-m-d H:i:s');


        $userData = Admin::getUserData();

        // 쿼리 짤 때
        // 셀렉할 때 *는 성능이 안좋음
        // 필요한 것만 셀렉
        // 게시판 page 로 GET 으로 전달할 때 파라미터로 받아서 페이지화 하는게 좋음
        // 더 보기 버튼 또는 페이지 나누기 (게시판 처럼)
//        foreach($userData as $row) {
//            echo $row['mem_idx']. ' - '. $row['mem_name']. ' - '. $row['mem_reg_dt']. ' - '. $row['mem_log_dt']. '<br />';
//        }

        View::render('Admin/index.php', [
            'userData' => $userData
        ]);
    }

    /**
     * Login 검사
     */
    public function loginCheckAction()
    {
        //        ★ View 에서 체크
//        if (empty($user_id) || empty($user_pw)) { // empty 로 빈값 체크
//            echo '<script> alert(" ❓아이디 또는 패스워드 입력하세요❓"); history.back(); </script>';
//        }

        // 각 변수에 ID, PW 저장
        $user_id = $_POST['user_id'];
        $user_pw = $_POST['user_password'];
        // 모델 에서 데이터 꺼내 오기
        $user = Login::getUserData($user_id);
        $pw_check = $user['mem_password'];
        $user_log = $user['mem_log_dt'];

        //만약 password 와 hash_pw 가 같다면 세션 실행
        if (password_verify($user_pw, $pw_check)) {

            session_start();
            $_SESSION["userID"] = $user_id;
            $_SESSION["userLog"] = (new DateTime())->format('Y-m-d H:i:s');
            View::render('Login/loginOK.html', []);
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