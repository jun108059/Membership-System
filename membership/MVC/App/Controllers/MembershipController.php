<?php

namespace App\Controllers;

use App\Models\Membership;
use \Core\View;
use DateTime;


class MembershipController extends \Core\Controller
{
    public function signUpAction()
    {
        // View 페이지 렌더링 해주기
        View::render('Membership/signUp.php',);

    }

    /** 가입 완료 버튼 -> DB data 넣기 */
    public function signUpDBAction()
    {
        // 필수 값 비어 있는지 확인
        if(empty( $_POST['id']) ||
            empty( $_POST['password']) ||
            empty( $_POST['email']) ||
            empty( $_POST['emadress']) ||
            empty( $_POST['name']) ||
            empty( $_POST['phone']) ||
            empty( $_POST['gender'] ))
        {
            echo '<script> alert("❌ 정보를 모두 입력해주세요 ❌"); history.back(); </script>';
            exit();
        }

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


        /************ 이메일 함수로 분리하고 삭제할 코드 ************/
        $hash = password_hash($userMail, PASSWORD_DEFAULT);

        $subjcet = "[멤버쉽 시스템] 인증 요청 메일입니다.";
        $content = "인증번호는 [ {$hash} ] 입니다.";
        $headers = "From: jun108059@naver.com\r\n";
        // 인증 이메일 전송
        mail($userMail,$subjcet, $content, $headers);
        /*********************************************************/

        // SignUp 완료 -> rendering
        View::render('Membership/signUpOK.php', [
        ]);

    }

    public function certificationAction()
    {
        /** 세션 정보를 유지 -> 바로 사용자 정보 뜨게 수정 */
        View::render('Membership/certificate.php', []);
    }

    public function sendMail($userMail)
    {
        $mail = new PHPMailer(true);

        try {

            // 서버 세팅
            $mail -> SMTPDebug = 3;    // 디버깅 설정
            $mail -> isSMTP();               // SMTP 사용 설정

            $mail -> Host = "smtp.naver.com";                      // email 보낼때 사용할 서버를 지정
            $mail -> SMTPAuth = true;                                // SMTP 인증을 사용함
            $mail -> Username = "jun108059@naver.com";  // 메일 계정
            $mail -> Password = "password";                   // 메일 비밀번호
            $mail -> SMTPSecure = "ssl";                             // SSL을 사용함
            $mail -> Port = 465;                                        // email 보낼때 사용할 포트를 지정
            $mail -> CharSet = "utf-8";                                // 문자셋 인코딩

            // 보내는 메일
            $mail -> setFrom("jun108059@naver.com", "transmit");

            // 받는 메일
            $mail -> addAddress("youngjun108059@gmail.com", "receive01");
            $mail -> addAddress($userMail, "receive02");

            $hash = password_hash($userMail, PASSWORD_DEFAULT);

            // 메일 내용
            $mail -> isHTML(true); // HTML 태그 사용 여부
            $mail -> Subject = "[멤버쉽 시스템] 인증 요청 메일입니다."; // 메일 제목
            $mail -> Body = "인증번호는 {$hash} 입니다.";    // 메일 내용
            $mail -> AltBody = "This is the plain text version of the email content";
//            /** 첨부 파일 */
//            $mail -> addAttachment("./test.zip");
//            $mail -> addAttachment("./image.jpg");

            // 메일 전송
            $mail -> send();

            echo "인증번호 전송 완료";

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error : ", $mail -> ErrorInfo;
        }
    }

}