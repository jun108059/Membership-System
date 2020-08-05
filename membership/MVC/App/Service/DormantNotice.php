<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "../vendor/phpmailer/phpmailer/src/SMTP.php";
require "../vendor/phpmailer/phpmailer/src/Exception.php";


class DormantNotice
{
    public static function mail($receiver, $userID)
    {
        $mail = new PHPMailer(true);
        try {
            // 서버세팅
            //디버깅 설정을 0 으로 하면 아무런 메시지가 출력되지 않습니다
            $mail->SMTPDebug = 0; // 디버깅 설정
            $mail->isSMTP(); // SMTP 사용 설정
            // 지메일일 경우 smtp.gmail.com, 네이버일 경우 smtp.naver.com
            $mail->Host = "smtp.naver.com";               // 네이버의 smtp 서버
            $mail->SMTPAuth = true;                         // SMTP 인증을 사용함
            $mail->Username = "jun108059@naver.com";    // 메일 계정 (지메일일경우 지메일 계정)
            $mail->Password = "**********";                  // 메일 비밀번호
            $mail->SMTPSecure = "ssl";                       // SSL을 사용함
            $mail->Port = 465;                                  // email 보낼때 사용할 포트를 지정
            $mail->CharSet = "utf-8"; // 문자셋 인코딩
            // 보내는 메일
            $mail->setFrom("jun108059@naver.com", "박영준");
            // 받는 메일
            $mail->addAddress($receiver, "receive01");
            $mail->addAddress("youngjun108059@gmail.com", "receive02");
            // 첨부파일
            //    $mail->addAttachment("./test1.zip");
            //    $mail->addAttachment("./test2.jpg");
            // 메일 내용

            $mail->isHTML(true); // HTML 태그 사용 여부
            $mail->Subject = "[멤버쉽 시스템] 휴면 전환 예정 알림 메일";  // 메일 제목
            $mail->Body = "안녕하세요. 멤버쉽 시스템 입니다.
            ‘정보통신망 이용촉진 및 정보보호에 관한 법률 제 29조’에 따라 회원님의 아이디가
            3일 뒤 휴면 전환 될 예정입니다.
            
            휴면으로 전환되는 경우 서비스 이용이 제한됩니다.
            아직은 아무 기능이 없지만요.😄
            원활한 서비스 이용을 위해 '멤버쉽 시스템' 에 로그인 해 주세요.
            
            고객님의 ID는 [ {$userID} ] 입니다.
            ";     // 메일 내용
            // Gmail로 메일을 발송하기 위해서는 CA인증이 필요하다.
            // CA 인증을 받지 못한 경우에는 아래 설정하여 인증체크를 해지하여야 한다.
            $mail->SMTPOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
                )
            );
            // 메일 전송
            $mail->send();
            return true;
//            echo "Message has been sent";
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error : ", $mail->ErrorInfo;
        }
    }
}