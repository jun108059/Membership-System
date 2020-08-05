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


        View::render('Admin/index.php', []);
    }


    /**
     * 전체 사용자 정보 보기
     * @return void
     */
    public function allUserInfoAction()
    {
//        print_r($_POST);
//        exit();
//        print($page);
        $pageNum = $this->route_params['page'];
//        $forum = Forum::findByTopicTitle();
        session_start();
        if(!isset($_SESSION['userID']) || $_SESSION['userLevel'] !== '1') {
            echo '<script> alert("🧨잘못된 접근입니다."); history.back(); </script>';
//    echo "<meta http-equiv='refresh' content='0; url=/'>";
            exit;
        }
        $_SESSION["userLog"] = (new DateTime())->format('Y-m-d H:i:s');

        $countUser = Admin::getAllPageNumber();


        // page 변수 : GET 으로 받은 데이터
        // 최초 페이지 $page = 1 할당
//        $page = ($_GET['page']) ? $_GET['page'] : 1;
        $page = ($pageNum) ? $pageNum : 1;

        $list = 5; // 페이지 당 데이터 수
        $block = 3; // 블록 당 페이지 수

        // PHP -> ceil() 메소드 = 올림
        $pageNum = ceil($countUser/$list); // 총 페이지
        $blockNum = ceil($pageNum/$block); // 총 블록
        $nowBlock = ceil($page/$block); // 현재 페이지가 위치한 블록 번호

        // 시작, 종료 페이지를 설정

        // 시작 페이지
        $s_page = ($nowBlock * $block) - ($block - 1);
//        $s_page = ($nowBlock * $block) - 2;

        // 시작 페이지가 음수 -> 1로 설정
        if ($s_page <= 1) {
            $s_page = 1;
        }
        // 종료 페이지
        $e_page = $nowBlock*$block;
        // 종료 페이지가 총 페이지 보다 많으면 -> 마지막 페이지로 설정
        if ($pageNum <= $e_page) {
            $e_page = $pageNum;
        }

        $s_point = ($page-1) * $list;

        $userData = Admin::getPageUserData($s_point, $list);

        View::render('Admin/allUserInfo.php', [
            'userData' => $userData,
            's_page' => $s_page,
            'e_page' => $e_page,
            'page' => $page,
            'list' => $list
        ]);
    }


    /**
     * User 회원 정보 수정 Page
     * @return bool
     */
    public function editUserAction() {

        $edit_id = $this->route_params['param'];
        $edit_user = [];
        if(!empty($edit_id))
        {
            // 수정할 User 정보 불러오기
            $edit_user = Admin::editUserData($edit_id);
//            print($edit_user['mem_email']);
//            exit();
        }
        else
        {
            echo '<script> alert("🧨잘못된 접근입니다."); history.back(); </script>';
        }

        session_start();
        if(!isset($_SESSION['userID']) || $_SESSION['userLevel'] !== '1') {
            echo '<script> alert("🧨잘못된 접근입니다."); history.back(); </script>';
//    echo "<meta http-equiv='refresh' content='0; url=/'>";
            exit;
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

        View::render('Admin/editForm.php', [
            'user_id'        => $edit_user['mem_user_id'],
            'user_email'     => $edit_user['mem_email'],
            'user_password'  => $edit_user['mem_password'],
            'user_status'    => $edit_user['mem_status'],
            'user_name'      => $edit_user['mem_name'],
            'user_phone'     => $edit_user['mem_phone'],
            'user_gender'    => $edit_user['mem_gender'],
            'user_level'     => $edit_user['mem_level'],
            'user_reg_dt'    => $edit_user['mem_reg_dt'],
            'user_log_dt'    => $edit_user['mem_log_dt'],
            'user_pw_dt'     => $edit_user['mem_pw_dt']
        ]);
        return true;
    }

    /**
     * 수정된 User 회원정보 저장!
     * @return boolean
     */
    public function userInfoUpdateAction()
    {
        // 필수 값 검사
        if (empty($_POST['password']) || empty($_POST['name']) || empty($_POST['phone'])) {
            return false;
        }

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id'   => $_POST['userId'],
            'mem_password'  => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'mem_name'      => $_POST['name'],
            'mem_phone'     => $_POST['phone'],
            'mem_pw_dt'     => $now, // 마지막 비밀 번호 변경 일시
            'mem_level'     => $_POST['level']
        ];

        /**
         * 수정된 User 데이터 Update
         */
        $user = Admin::userInfoUpdate($userData);


        View::render('Admin/index.php', []);
        return true;

    }

    /**
     * User 강제 탈퇴!
     * @return boolean
     */
    public function deleteUserAction()
    {
        $delete_id = $this->route_params['param'];
        $delete_user = [];
        if(!empty($delete_id))
        {
            // 탈퇴 시킬 User 정보 불러오기
            $edit_user = Admin::editUserData($delete_id);
        }
        else
        {
            echo '<script> alert("🧨잘못된 접근입니다."); history.back(); </script>';
        }

        session_start();
        if(!isset($_SESSION['userID']) || $_SESSION['userLevel'] !== '1') {
            echo '<script> alert("🧨잘못된 접근입니다."); history.back(); </script>';
//    echo "<meta http-equiv='refresh' content='0; url=/'>";
            exit;
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

        $delete_reason = "관리자에 의한 탈퇴";

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id'   => $delete_id,
            'mem_log_dt'    => $now,
            'reason_detail' => $delete_reason
        ];

        /** User 강제 DELETE */
        Admin::deleteInfo($userData);

        View::render('Admin/index.php', []);
        return true;

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