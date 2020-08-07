<?php

namespace App\Controllers;

use App\Models\Login;
use App\Models\Membership;
use App\Service\MailerService;
use \Core\View;
use DateTime;
use Exception;


class MembershipController extends \Core\Controller
{
    /**
     * 1번 : 이메일 인증 Page
     */
    public function certifyEmailAction()
    {
        // View 페이지 렌더링 해주기
        View::render('Membership/certifyEmail.php');
    }

    /**
     * 2번 : 이메일 인증 번호 전송
     * @throws Exception
     */
    public function sendMailAction()
    {
        $resultArray = ['result' => 'fail', 'alert' => ''];

        if (empty($_POST['email']) || empty($_POST['emAddress'])) {
            $resultArray['alert'] = '🧨이메일을 입력해주세요';
            echo json_encode($resultArray);
            exit();
        }

        $userMail = $_POST['email'] . '@' . $_POST['emAddress'];

        if (Membership::isEmailExisted($userMail)) {
            $resultArray['alert'] = '🟡 이미 사용 중인 Email 입니다. 🟡';
            echo json_encode($resultArray);
            exit();
        }

        $certify = random_int(100000, 999999); // 인증 번호 random 생성

        $mailReturn = MailerService::mail($userMail, $certify);

        if ($mailReturn) {
            $resultArray['result'] = 'success';
            $resultArray['cert_num'] = $certify;
        }

        echo json_encode($resultArray);
        exit;
    }

    /**
     * 3번 : 회원 가입 Page Render
     */
    public function signUpAction()
    {
        if (empty($_POST['email']) || empty($_POST['emadress'])) {
            // Error Handling
            View::render('Error/errorPage.php', [
                'alert' => "이메일 정보를 입력해주세요.",
                'back' => "true"
            ]);
            exit();
        }
        $userMail = $_POST['email'] . "@" . $_POST['emadress'];

        // View 페이지 렌더링 해주기
        View::render('Membership/signUp.php', [
            'email' => $userMail
        ]);
    }

    /** 4번 : 가입 완료 버튼 -> DB data 넣기 */
    public function signUpDBAction()
    {
        // 필수 값 검사
        if (empty($_POST['userId']) || empty($_POST['email']) || empty($_POST['name'])
            || empty($_POST['phone'])) {
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다.",
                'back' => "true"
            ]);
            exit();
        }

        if (!MembershipController::passwordCheck($_POST['password'])) {
            View::render('Error/errorPage.php', [
                'alert' => "비밀번호 형식에 맞게 입력해주세요.",
                'back' => "true"
            ]);
        }

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id'   => $_POST['userId'],
            'mem_email'     => $_POST['email'],
            'mem_password'  => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'mem_status'    => 'Y', // enum 타입 - 정상 가입
            'mem_dor_mail'  => 'N', // enum 타입 - 휴면 메일 전송 여부
            'mem_name'      => $_POST['name'],
            'mem_phone'     => $_POST['phone'],
            'mem_gender'    => (!empty($_POST['gender'])) ?: '', // enum 타입
            'mem_level'     => 4, // 일반 사용자 level 4
            'mem_reg_dt'    => $now, // 회원 가입 일시
            'mem_log_dt'    => $now, // 마지막 로그인 일시
            'mem_pw_dt'     => $now, // 마지막 비밀 번호 변경 일시
        ];

        // 데이터 DB INSERT
        $result = Membership::insertInfo($userData);

        // 정상적으로 INSERT 되지 않을 경우
        if (empty($result)){
            View::render('Error/errorPage.php', [
                'alert' => "회원정보가 정상적으로 저장되지 않았습니다. 다시 시도해주세요.",
                'back' => "true"
            ]);
        }

        // SignUp 완료 -> rendering
        View::render('Membership/signUpOK.php', [
            'id' => $userData['mem_user_id'],
            'name' => $userData['mem_name'],
            'email' => $userData['mem_email']
        ]);
    }

    /***************************** 중복 검사 시작  ************************************/

    /**
     * ID 중복 검사(AJAX)
     */
    public function checkIdAction()
    {
        $resultArray = ['status' =>'check', 'mention' => ''];

        if (empty($_POST['userId'])){
            $resultArray['mention'] = '🧨아이디를 입력해주세요!';
            echo json_encode($resultArray);
            exit;
        }

        if(!preg_match("/^[a-z0-9]{4,30}/i", $_POST['userId'])) {
            $resultArray['mention'] = '🔴4~30자 영문자 또는 숫자 입력🔴';
            $resultArray['status'] = 'check';
            echo json_encode($resultArray);
            exit;
        }else if (Membership::isUserExisted($_POST['userId'])) {
            $resultArray['mention'] = '🔴이미 사용중인 아이디입니다.🔴';
            $resultArray['status'] = 'disable';
            echo json_encode($resultArray);
            exit;
        } else {
            $resultArray['mention'] = '🟢사용 가능한 ID 입니다.🟢';
            $resultArray['status'] = 'available';
            echo json_encode($resultArray);
            exit;
        }

    }

    /**
     * 핸드폰 번호 중복 검사(AJAX)
     */
    public function checkPhoneAction()
    {
        $resultArray = ['status' =>'check', 'mention' => ''];

        if (empty($_POST['phone'])){
            $resultArray['mention'] = '🧨전화번호를 입력해주세요!';
            echo json_encode($resultArray);
            exit;
        }
        if(!preg_match("/(010|011|016|017|018|019)-[0-9]{4}-[0-9]{4}/", $_POST['phone'])) {
            $resultArray['mention'] = '❌전화번호 형식으로 입력❌';
            $resultArray['status'] = 'check';
            echo json_encode($resultArray);
            exit;
        }else if (Membership::isPhoneExisted($_POST['phone'])) {
            $resultArray['mention'] = '🔴이미 가입된 번호입니다.🔴';
            $resultArray['status'] = 'disable';
            echo json_encode($resultArray);
            exit;
        } else {
            $resultArray['mention'] = '🟢사용 가능한 번호입니다.🟢';
            $resultArray['status'] = 'available';
            echo json_encode($resultArray);
            exit;
        }
    }

    /***************************** 중복 검사 끝    ************************************/

    /***************************** ID/PW 찾기 시작 ************************************/

    /**
     * 아이디/비밀번호 찾기 Page Render
     */
    public function findMyInfoAction()
    {
        View::render('Membership/findMyInfo.php');
    }

    /**
     * ID 찾기(AJAX) 아이디 alert 창 띄우기
     */
    public function findIdAction()
    {
        $resultArray = ['result'=>'fail', 'status' =>'check', 'alert' => '', 'userID' => ''];

        // front 검사 + Back 이중 검사
        if (empty($_POST['email']) || empty($_POST['emadress'])) {
            $resultArray['alert'] = '이메일을 입력해주세요.';
            echo json_encode($resultArray);
            exit;
        }

        if (empty($_POST['name'])) {
            $resultArray['alert'] = '이름을 입력해주세요.';
            echo json_encode($resultArray);
            exit;
        }

        if (Membership::isEmailExisted($_POST['email'] . "@" . $_POST['emadress'])) {
            // 입력한 이메일 - DB에 존재 한다면
            $userMail = $_POST['email'] . "@" . $_POST['emadress'];
            // 이름 <-> Email 정보 일치 여부 확인 (T/F)
            $checkNameEmailRight = Membership::checkNameEmailRight($_POST['name'], $userMail);
            // 일치 한다면
            if ($checkNameEmailRight) {
                $userId = Membership::findId($userMail);
                // View 페이지 렌더링 해주기
                $resultArray['result'] = 'success';
                $resultArray['userID'] = $userId;
                echo json_encode($resultArray);
                exit;
            } else {
                // 이름 <-> 이메일 = 일치하지 않는다면
                $resultArray['alert'] = '가입된 이메일과 이름이 일치하지 않습니다❗';
                echo json_encode($resultArray);
                exit;
            }
        } else {
            // 존재하지 않는 Email인 경우 튕겨주기
            $resultArray['alert'] = '가입된 이메일이 아닙니다❗';
            echo json_encode($resultArray);
            exit;
        }
    }

    /**
     * PW 찾기를 위한 인증 메일 전송
     * @throws Exception
     */
    public function emailForFindPwAction()
    {
        $resultArray = ['result' => 'fail', 'alert' => ''];

        if (empty($_POST['user_id'])) {
            // User Id값 없이 접근한 경우
            $resultArray['alert'] = '🧨올바른 접근이 아닙니다.';
            echo json_encode($resultArray);
            exit();
        } elseif (Membership::isUserExisted($_POST['user_id'])) {
            // Id값 존재 && 올바른 User ID를 입력한 경우
            // 비밀 번호 찾을 User 조회
            $user = Login::getUserData($_POST['user_id']);
            // 본인 인증 보낼 메일 값
            $userMail = $user['mem_email'];
            // 인증 번호 random 생성
            $certify = random_int(100000, 999999);
            // 메일 전송 성공 여부 (T/F)
            $mailReturn = MailerService::mail($userMail, $certify);
            $mailType = "비밀번호 찾기 - 본인인증";
            if ($mailReturn) {
                // log 기록
                Membership::emailSendLog($user, $mailType);
                $resultArray['result'] = 'success';
                $resultArray['cert_num'] = $certify;
            }

            echo json_encode($resultArray);
            exit;
        } else {
            $resultArray['alert'] = '🧨존재하지 않는 아이디입니다.';
            echo json_encode($resultArray);
            exit();
        }
    }

    /**
     * 비밀번호 재설정 Page Render
     */
    public function passwordChangeAction()
    {
        // Id값 존재 && 올바른 User ID를 입력하지 않은 경우 Error Handling
        if(empty($_POST['user_id']) && Membership::isUserExisted($_POST['user_id'])){
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다❗",
                'back' => "true"
            ]);
            exit();
        }

        // user 정보 조회
        $user = Login::getUserData($_POST['user_id']);

        if (empty($user)){
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다❗",
                'back' => "true"
            ]);
            exit();
        }

        View::render('Membership/passwordChange.php', [
            'user_id' => $user['mem_user_id'],
            'user_pw' => $user['mem_password']
        ]);

    }


    /**
     * 비밀번호 재설정 이후 DB 저장!
     */
    public function newPwToDBAction()
    {
        // 필수 값 검사
        if (empty($_POST['user_id']) ) {
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다!",
                'back' => "true"
            ]);
            exit();
        }

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id' => $_POST['user_id'],
            'mem_password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'mem_pw_dt' => $now, // 마지막 비밀 번호 변경 일시
        ];

        // 데이터 Update
        if(!Membership::changePassword($userData)) {
            // Error Handling
            View::render('Error/errorPage.php', [
                'alert' => "비밀번호 재설정 오류가 발생했습니다. 다시 시도해주세요.",
                'back' => "true"
            ]);
            exit();
        }

        View::render('/Membership/passwordChangeOK.php');
    }

    /***************************** ID/PW 찾기 끝 ************************************/

    /***************************** 개인정보수정 시작 **********************************/

    /**
     * 개인정보 변경 DB 저장!
     */
    public function newInfoToDBAction()
    {
        // 필수 값 검사
        if (empty($_POST['password']) || empty($_POST['name']) || empty($_POST['phone'])) {
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다!",
                'back' => "true"
            ]);
        }

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id'   => $_POST['user_id'],
            'mem_password'  => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'mem_name'      => $_POST['name'],
            'mem_phone'     => $_POST['phone'],
            'mem_gender'    => $_POST['gender'],
            'mem_pw_dt'     => $now, // 마지막 비밀 번호 변경 일시
            'mem_log_dt'    => $now
        ];

        // 데이터 Update
        if(!Membership::changeInfo($userData)) {
            // Error Handling
            View::render('Error/errorPage.php', [
                'alert' => "개인정보 변경에 실패했습니다. 다시 시도해주세요.",
                'back' => "true"
            ]);
            exit();
        }
        View::render('Home/index.php');
    }

    /***************************** 개인정보수정 끝 **********************************/

    /***************************** 회원 탈퇴 시작 **********************************/

    /**
     * 회원 탈퇴 페이지 이동
     */
    public function withDrawPageAction()
    {
        if (empty($_SESSION['userID'])) {
            // 세션 없이 접근 Error Handling
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다.",
                'back' => "true"
            ]);
            exit();
        }
        $user_id = $_SESSION['userID'];
        $user = Membership::checkPassword($user_id); // 비밀번호 일치 검사
        $user_pw = $user['mem_password'];
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id'   => $user_id,
            'mem_user_pw'   => $user_pw,
            'mem_log_dt'    => $now,
        ];

        View::render('Membership/withdraw.php', [
            'user_id' => $userData['mem_user_id'],
            'user_pw' => $userData['mem_user_pw'],
            'log_datetime' => $userData['mem_log_dt']
        ]);
        return true;
    }

    /**
     * 회원 탈퇴 로직
     */
    public function withDrawAction()
    {
        $resultArray = [
            'result' => 'fail',
            'alert' => '',
            'userId' => $_SESSION['userID'],
            'reason' => $_POST['reason']
        ];

        if (empty($_SESSION['userID']) || empty($_POST['reason'])) {
            $resultArray['alert'] = '🧨잘못된 접근입니다.';
            echo json_encode($resultArray);
            exit();
        }

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id'   => $_SESSION['userID'],
            'mem_log_dt'    => $now,
            'reason_detail' => $_POST['reason']
        ];

        // 회원 정보 DELETE
        $deleteReturn = Membership::deleteInfo($userData);

        if ($deleteReturn) {
            $resultArray['result'] = 'success';
        }
        session_destroy();

        echo json_encode($resultArray);
        exit;
    }

    /**
     * 실시간 비밀번호 일치 여부 검사(AJAX)
     */
    public function checkPwAction()
    {
        $resultArray = ['status' =>'check', 'mention' => ''];

        if (empty($_POST['currPw'])){
            $resultArray['mention'] = '🧨비밀번호를 입력해주세요!';
            echo json_encode($resultArray);
            exit;
        }

        $user = Login::getUserData($_POST['userId']);
        $pw_check = $user['mem_password'];
        if (password_verify($_POST['currPw'], $pw_check)) {
            $resultArray['mention'] = '🟢비밀번호가 일치합니다.🟢';
            $resultArray['status'] = 'available';
            echo json_encode($resultArray);
            exit;
        }else{
            $resultArray['mention'] = '🔴일치하지 않습니다.🔴';
            $resultArray['status'] = 'disable';
            echo json_encode($resultArray);
            exit;
        }
    }


    /***************************** 회원 탈퇴 끝 **********************************/

    /**
     * 비밀 번호 유효성 검사 함수
     * @param $password
     * @return bool
     */
    private function passwordCheck ($password)
    {
        if (empty($password)){
            return false;
        }

        if (strlen($password) < 8 || strlen($password) > 21 || preg_match("/\s/u", $password) == true ) {
            return false;
        }

        return true;
    }
}