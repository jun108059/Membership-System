<?php
namespace App\Controllers;

use App\Models\Dormant;
use App\Models\Login;
use App\Models\Membership;
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
        if (! $this->checkAdminLogin()) {
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다!",
                'back' => "true"
            ]);
            exit();
        }
        View::render('Admin/index.php');
    }

    /**
     * 전체 사용자 정보 보기
     * @return void
     */
    public function allUserInfoAction()
    {
        if (! $this->checkAdminLogin()) {
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다!",
                'back' => "true"
            ]);
            exit();
        }
        // param 으로 전달 받은 현재 page:0 이면 >> 1 page 할당
        $pageNum = (!empty($this->route_params['page']))? $this->route_params['page'] : 1;
        $countUser = Admin::getAllPageNumber(); // 총 User 수

        $viewList = 5; // 페이지 당 보여질 User 5명
        $block = 3; // 페이지 3개씩 한묶음

        // PHP -> ceil() 메소드 = 올림
        $totalPage = ceil($countUser/$viewList); // 총 페이지 수
        $nowBlock = ceil($pageNum/$block); // 현재 페이지가 위치한 블록 번호

        // 시작 페이지
        $startPage = ($nowBlock * $block) - ($block - 1);
        // 종료 페이지
        $endPage = $nowBlock * $block;

        $startPoint = ($pageNum-1) * $viewList; // (현재 페이지 - 1) * (보여지는 list 수)

        $userData = Admin::getPageUserData($startPoint, $viewList); // 시작지점 ~ viewList 만큼

        View::render('Admin/allUserInfo.php', [
            'userData' => $userData,
            'startPage' => ($startPage <= 1)? 1: $startPage, // 시작 페이지가 음수 -> 1로 설정
            'endPage' => ($totalPage <= $endPage)? $totalPage: $endPage, // 종료 페이지가 총 페이지 보다 많으면 마지막 페이지로 설정
            'page' => $pageNum,
            'list' => $viewList
        ]);
    }

    /**
     * User 회원 정보 수정 Page
     * @return void
     */
    public function editUserAction()
    {
        if (! $this->checkAdminLogin()) {
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다!",
                'back'  => "true"
            ]);
            exit();
        }
        // 수정할 User @param 으로 ID 받기
        $editID = $this->route_params['param'];

        if (empty($editID)){
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다!",
                'back' => "true"
            ]);
            exit();
        }

        // 수정할 User 정보 불러오기
        $edit_user = Admin::editUserData($editID);

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
    }

    /**
     * 수정된 User 회원정보 저장!
     */
    public function userInfoUpdateAction()
    {
        // 필수 값 검사
        if (empty($_POST['password']) || empty($_POST['name']) || empty($_POST['phone'])) {
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다!",
                'back' => "true"
            ]);
            exit();
        }

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id'   => $_POST['userId'],
            'mem_password'  => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'mem_name'      => $_POST['name'],
            'mem_phone'     => $_POST['phone'],
            'mem_pw_dt'     => $now, // 마지막 비밀 번호 변경 일시
            'mem_level'     => (empty($_POST['level'])) ? $_POST['level'] : 4
        ];

        Admin::userInfoUpdate($userData); //수정된 User 데이터 Update
        View::render('Admin/index.php');
    }

    /**
     * User 강제 탈퇴!
     * @return void
     */
    public function deleteUserAction()
    {
        if (! $this->checkAdminLogin()) {
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다!",
                'back'  => "true"
            ]);
            exit();
        }

        $delete_id = $this->route_params['param'];
        if(empty($delete_id)) {
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다!",
                'back' => "true"
            ]);
            exit();
        }

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = Login::getUserData($delete_id);
        if($userData['mem_status'] === 'N') {
            echo "<script> alert('이미 탈퇴한 회원입니다.'); history.back();</script>";
        }
        $userData['mem_log_dt'] = $now;
        $userData['reason_detail'] = "관리자에 의한 탈퇴";
        $userData['mem_status'] = 'N';
        $deleteType = "F"; // 관리자 탈퇴

        // 회원 정보 DELETE
        $insertResult = Dormant::insertWithdraw($userData, $deleteType);
        $stateChange  = Dormant::stateToDelete($userData);
        $deleteResult = Dormant::deleteUserData($userData);
        if ($insertResult && $stateChange && $deleteResult) {
            View::render('Admin/index.php');
        }else {
            echo "<script> alert('회원 정보 삭제에서 오류가 발생하였습니다.'); history.back();</script>";
        }

    }

    /**
     * 관리자 로그인 체크
     */
    private function checkAdminLogin ()
    {
        if (empty($_SESSION['userID']) || empty($_SESSION['userLevel']) || $_SESSION['userLevel'] !== '1') {
            return false;
        }

        return true;
    }
}