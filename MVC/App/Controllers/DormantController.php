<?php

namespace App\Controllers;

use App\Models\Dormant;
use App\Models\Membership;
use App\Service\DormantNotice;
use App\Service\MailerServiceForDormant;
use \Core\View;
use DateTime;
use Exception;


class DormantController extends \Core\Controller
{
    /**
     * 30일 전 휴면 계정 알림 메일 전송 - 휴면 계정 바로 전환
     */
    public function dormantNoticeMailAction()
    {
        // 휴면 계정인지 검사한 user 데이터 가져오기
        $userRow = Dormant::getDormantUser();
        $mailType = "휴면 전환 예정 알림";
        foreach ($userRow as $row) {
            $mailResult = DormantNotice::mail($row['mem_email'], $row['mem_user_id']);
            if(!$mailResult) {
                View::render('Error/errorPage.php', [
                    'alert' => "이메일 전송에 실패했습니다. 다시 시도해주세요.",
                    'back' => "true"
                ]);
                exit;
            }
            $row['mem_dor_mail'] = 'Y';
            // 바로 휴면 계정으로 전환
            $row['mem_status'] = 'H';
            $logResult = Membership::emailSendLog($row, $mailType);
            if(!$logResult) {
                View::render('Error/errorPage.php', [
                    'alert' => "이메일 전송 로그 저장 오류가 발생했습니다. 다시 시도해주세요.",
                    'back' => "true"
                ]);
                exit;
            }
            $dormantResult = Dormant::convertDormant($row);
            if(!$dormantResult) {
                View::render('Error/errorPage.php', [
                    'alert' => "휴면 전환 중 오류가 발생했습니다. 다시 시도해주세요.",
                    'back' => "true"
                ]);
                exit;
            }
        }
    }

    /**
     * 계정 복구 인증 메일 전송
     * @throws Exception
     */
    public function dormantReturnMailAction()
    {
        $resultArray = ['result' => 'fail', 'alert' => ''];

        if(empty($_POST['email'])) {
            $resultArray['alert'] = '🧨잘못된 접근입니다.';
            echo json_encode($resultArray);
            exit();
        }

        $userMail = $_POST['email'];

        if (!Membership::isEmailExisted($userMail)) {
            $resultArray['alert'] = '🧨가입되지 않은 이메일입니다.';
            echo json_encode($resultArray);
            exit();
        }

        $certify = random_int(100000, 999999); // 인증 번호 random 생성

        $mailReturn = MailerServiceForDormant::mail($userMail, $certify);

        if ($mailReturn) {
            $resultArray['result'] = 'success';
            $resultArray['cert_num'] = $certify;
        }

        echo json_encode($resultArray);
        exit;
    }

    /** 휴면 해제 완료 -> DB data 수정 */
    public function dormantReleaseAction()
    {
        // 필수 값 검사
        if (empty($_POST['email'])) {
            View::render('Error/errorPage.php', [
                'alert' => "잘못된 접근입니다.",
                'back' => "true"
            ]);
        }
        // 휴면 Table 에서 User 정보 가져오기
        $userData = Dormant::getUserInfo($_POST['email']);

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData['mem_dor_mail'] = 'N'; // enum 타입 - 휴면 메일 전송 여부
        $userData['mem_log_dt'] = $now; // 마지막 로그인 일시
        $userData['mem_status'] = 'Y';

        // 휴면 해제 -> 회원 복구 DB 저장
        $dormantType = "OUT";
        if(Dormant::releaseDormant($userData)) {
            // 회원 복구 성공 시 delete 휴면 계정
            if(!Dormant::deleteDormant($userData)) {
                View::render('Error/errorPage.php', [
                    'alert' => "오류가 발생했습니다. 휴면 해제를 재시도 해주세요.",
                    'back' => "true"
                ]);
                exit();
            } else {
                // Delete 성공 시 휴면 로그 테이블 저장 성공
                if(!Dormant::logDormantTable($userData, $dormantType)){
                    View::render('Error/errorPage.php', [
                        'alert' => "로그 저장 오류가 발생했습니다.",
                        'back' => "true"
                    ]);
                    exit();
                }
            }
        }
        // 휴면 해제 완료 -> 로그인 페이지
        View::render('Login/index.php');
    }
}