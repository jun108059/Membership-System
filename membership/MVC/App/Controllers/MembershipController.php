<?php

namespace App\Controllers;

use App\Models\Membership;
use \Core\View;
use DateTime;


class MembershipController extends \Core\Controller
{
    public function signUpEmailAction()
    {
        // View 페이지 렌더링 해주기
        View::render('Membership/signUpEmail.php',);
    }

    public function sendMailAction(){
        // 입력된 email 값 POST로 받기
        $userMail = $_POST['email'] . '@' . $_POST['emadress'];

        $certify = random_int(100000, 999999);

        MailerController::mail($userMail, $certify);

        View::render('Membership/email.php',[
            'mail' => $userMail,
            'certify' => $certify
        ]);
    }
    /** 가입 완료 버튼 -> DB data 넣기 */
    public function signUpDBAction()
    {
        // 비밀 번호 유효성 검사
        MembershipController::passwordCheck($_POST['password']);

        /************************************************************/
        /** ♻ 중복체크 부분 모두 ajax 비동기 처리로
         * Front 에서 처리할 수 있도록 수정하기
         */

        // User ID 중복 체크
        if (Membership::isUserExisted($_POST['id'])) {
            echo '<script> alert("🔴 이미 사용 중인 ID입니다. 🔴"); history.back(); </script>';
            exit();
        }

        $userMail = $_POST['email'] . '@' . $_POST['emadress'];
        // User E-mail 중복 체크
        if (Membership::isEmailExisted($userMail)) {
            echo '<script> alert("🟡 이미 사용 중인 Email입니다. 🟡"); history.back(); </script>';
            exit();
        }

        // User 전화번호 중복 체크
        if (Membership::isPhoneExisted($_POST['phone'])) {
            echo '<script> alert("🟠 이미 가입된 전화번호입니다. 🟠"); history.back(); </script>';
            exit();
        }

        /************************************************************/

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id' => $_POST['id'],
            'mem_email' => $_POST['email'] . '@' . $_POST['emadress'],
            'mem_password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'mem_status' => 'Y', // enum 타입 - 정상 가입
            'mem_cert' => 'N', // enum 타입 - 본인 인증 여부 디폴트 = N
            'mem_name' => $_POST['name'],
            'mem_phone' => $_POST['phone'],
            'mem_gender' => $_POST['gender'], // enum 타입
            'mem_level' => 4, // 일반 사용자 level 4
            'mem_reg_dt' => $now, // 회원 가입 일시
            'mem_log_dt' => $now, // 마지막 로그인 일시
            'mem_pw_dt' => $now, // 마지막 비밀 번호 변경 일시
            // 이메일 인증 위한 hash
            'certify' => password_hash($_POST['email'] . '@' . $_POST['emadress'], PASSWORD_DEFAULT)
        ];

        /** 데이터 Insert
         * 서비스 할 때 사용자 한테 회원가입 page로 다시 안내
         * 관리자는 로그 파일로 에러 처리할 수 있도록 if else 설계
         */
        Membership::insertInfo($userData);

        /**세션 정보 넣는 시점!*/

        // SignUp 완료 -> rendering
        View::render('Membership/signUpOK.php', []);

    }

    public function certificationAction()
    {
        /** 세션 정보를 유지 -> 바로 사용자 정보 뜨게 수정 */
        View::render('Membership/certificate.php', []);
    }



    /**
     * 비밀 번호 유효성 검사 함수
     * @param $_password
     */
    protected function passwordCheck($_password)
    {
        $pw = $_password;
        $num = preg_match('/[0-9]/u', $pw);
        $eng = preg_match('/[a-z]/u', $pw);
        $spe = preg_match("/[\!\@\#\$\%\^\&\*]/u",$pw);

        if(strlen($pw) < 8 || strlen($pw) > 21)
        {
            echo '<script> alert("🔴 비밀번호는 8자리 ~ 20자리 이내로 입력해주세요. 🔴"); history.back(); </script>';
            exit();
        }

        if(preg_match("/\s/u", $pw) == true)
        {
            echo '<script> alert("🟡 비밀번호는 공백없이 입력해주세요. 🟡"); history.back(); </script>';
            exit();
        }

        if( $num == 0 || $eng == 0 || $spe == 0)
        {
            echo '<script> alert("🟠 비밀번호는 영문, 숫자, 특수문자를 혼합하여 입력해주세요. 🟠"); history.back(); </script>';
            exit();
        }
    }
}