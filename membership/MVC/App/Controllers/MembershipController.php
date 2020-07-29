<?php

namespace App\Controllers;

use App\Models\Membership;
use App\Service\MailerService;
use \Core\View;
use DateTime;


class MembershipController extends \Core\Controller
{
    /**
     * 1번 : 이메일 인증 Page
     * Render - View
     */
    public function signUpEmailAction()
    {
        // View 페이지 렌더링 해주기
        View::render('Membership/1.signUpEmail.php',);
    }

    /**
     * 2번 : 이메일 인증 번호 전송
     * Check - 메일 중복 & 인증 번호 전송
     * Render - VIew
     * @throws Exception
     */
    public function sendMailAction()
    {
//        header("Content-Type: application/json");
        // 입력된 email 값 POST로 받기
        $userMail = $_POST['email'] . '@' . $_POST['emadress'];

        if (Membership::isEmailExisted($userMail)) {
            echo '<script> alert("🟡 이미 사용 중인 Email 입니다. 🟡"); history.back(); </script>';
            exit();
        }

        $certify = random_int(100000, 999999); // 인증 번호 random 생성
//        $certify = $_POST['cert_num'];
//        MailerService::mail($userMail, $certify);
        echo "메일 보내는 함수 주석 처리";
        // View 페이지 렌더링 해주기
        /*
        View::render('Membership/3.signUp.php', [
            'mail' => $userMail
        ]);
        */
        View::render('Membership/2.emailCertify.php', [
            'mail' => $userMail,
            'certify' => $certify
        ]);

    }

    /**
     * 3번 : 회원 가입 Page Render
     * Render - View Sign Up
     */
    public function signUpAction()
    {
        $userMail = $_POST['email'];
        // View 페이지 렌더링 해주기
        View::render('Membership/3.signUp.php', [
            'mail' => $userMail
        ]);
    }

    /** 4번 : 가입 완료 버튼 -> DB data 넣기 */
    public function signUpDBAction()
    {
        // 비밀 번호 유효성 검사 - Script 에서 튕기는 코드 작성 후 삭제
        MembershipController::passwordCheck($_POST['password']);

        // 필수 값 검사
        if (empty($_POST['userId']) || empty($_POST['email']) || empty($_POST['password'])
            || empty($_POST['name']) || empty($_POST['phone'])) {
            return false;
        }

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id'   => $_POST['userId'],
            'mem_email'     => $_POST['email'],
            'mem_password'  => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'mem_status'    => 'Y', // enum 타입 - 정상 가입
            'mem_cert'      => 'Y', // enum 타입 - 본인 인증 여부
            'mem_name'      => $_POST['name'],
            'mem_phone'     => $_POST['phone'],
            'mem_gender'    => (!empty($_POST['gender'])) ? : '', // enum 타입
            'mem_level'     => 4, // 일반 사용자 level 4
            'mem_reg_dt'    => $now, // 회원 가입 일시
            'mem_log_dt'    => $now, // 마지막 로그인 일시
            'mem_pw_dt'     => $now, // 마지막 비밀 번호 변경 일시
        ];

        /**
         * 데이터 Insert
         * 서비스 할 때 사용자 한테 회원가입 page로 다시 안내
         * 관리자는 로그 파일로 에러 처리할 수 있도록 if else 설계
         */
        Membership::insertInfo($userData);

        // SignUp 완료 -> rendering
        View::render('Membership/4.signUpOK.php', [
            'id' => $userData['mem_user_id'],
            'name' => $userData['mem_name'],
            'email' => $userData['mem_email']
        ]);
        return true;
    }

    public function checkIdAction()
    {
        if (!empty($_POST['userId'])) {
            if (Membership::isUserExisted($_POST['userId'])) {
//            echo '<script> alert("🔴 이미 사용 중인 ID입니다. 🔴"); history.back(); </script>';
                echo "<span class='status-not-available'> 🔴이미 사용 중인 ID입니다.🔴</span>";
//            exit();
            } else {
//                echo "사용 가능한 ID 입니다";
                echo "<span class='status-available'> 🟢사용 가능한 ID 입니다.🟢</span>";
            }
        }
    }

    public function checkEmailAction()
    {
        if (!empty($_POST['email'])) {
            if (Membership::isEmailExisted($_POST['email'])) {
//            echo '<script> alert("🔴 이미 사용 중인 ID입니다. 🔴"); history.back(); </script>';
                echo "<span class='status-not-available'> Username Not Available.</span>";
//            exit();
            } else {
//                echo "사용 가능한 ID 입니다";
                echo "<span class='status-available'> Username Available.</span>";
            }
        }
    }

    public function checkPhoneAction()
    {
        if (!empty($_POST['phone'])) {
            if (Membership::isPhoneExisted($_POST['phone'])) {
                echo "<span class='status-not-available'> 🔴이미 가입된 번호입니다.🔴</span>";
            } else {
                echo "<span class='status-available'> 🟢사용 가능한 번호입니다.🟢</span>";
            }
        }
    }

    /**
     * (삭제 예정) 비밀 번호 유효성 검사 함수
     * @param $_password
     */
    protected function passwordCheck($_password)
    {
        $pw = $_password;
        $num = preg_match('/[0-9]/u', $pw);
        $eng = preg_match('/[a-z]/u', $pw);
        $spe = preg_match("/[\!\@\#\$\%\^\&\*]/u", $pw);

        if (strlen($pw) < 8 || strlen($pw) > 21) {
            echo '<script> alert("🔴 비밀번호는 8자리 ~ 20자리 이내로 입력해주세요. 🔴"); history.back(); </script>';
            exit();
        }

        if (preg_match("/\s/u", $pw) == true) {
            echo '<script> alert("🟡 비밀번호는 공백없이 입력해주세요. 🟡"); history.back(); </script>';
            exit();
        }
        // 테스트 때문에 삭제 - 특수 문자 그냥 뺄지 고민 중
//        if ($num == 0 || $eng == 0 || $spe == 0) {
//            echo '<script> alert("🟠 비밀번호는 영문, 숫자, 특수문자를 혼합하여 입력해주세요. 🟠"); history.back(); </script>';
//            exit();
//        }
    }
}