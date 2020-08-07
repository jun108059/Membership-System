<?php

namespace App\Controllers;

use App\Models\Dormant;
use App\Models\Membership;
use App\Service\DormantNotice;
use App\Service\DormantScheduleNotice;
use App\Service\MailerServiceForDormant;
use \Core\View;
use DateTime;
use Exception;


class DormantController extends \Core\Controller
{
    /**
     * (15일 미접속) 휴면 계정 알림 메일 전송 - 휴면 계정 바로 전환
     */
    public function dormantNoticeMailAction()
    {
        // 휴면 계정 예정인지 검사한 user 데이터 가져오기(15일 미접속)
        $userRow = Dormant::getSoonDormantUser();
        $mailType = "15일 뒤 휴면 전환 예정 알림";
        $count = 0;
        foreach ($userRow as $row) {
            if($row['mem_dor_mail'] === 'N') { // 휴면 메일을 받은 적이 없다면
                $mailResult = DormantScheduleNotice::mail($row['mem_email'], $row['mem_user_id']);
                $logResult = Membership::emailSendLog($row, $mailType);
                if(!$logResult || !$mailResult) {
                    // 메일 전송 또는 로그 저장에 문제가 발생한 경우
                    View::render('Error/errorPage.php', [
                        'alert' => "이메일 전송에 실패했습니다. 다시 시도해주세요.",
                        'back' => "true"
                    ]);
                    exit;
                }
                $row['mem_dor_mail'] = 'Y'; // 휴면 메일 보낸 상태
                if(!Dormant::noticeMailSendStatus($row)){
                    View::render('Error/errorPage.php', [
                        'alert' => "휴면 메일 발송 상태 저장 중 발생했습니다.",
                        'back' => "true"
                    ]);
                    exit;
                }
                $count = $count + 1;
            }
        }
        echo "<script> alert('메일 ['+$count+']개 전송이 완료되었습니다.'); history.back();</script>";
    }

    /**
     * (30일 미접속) 휴면 계정 전환
     */
    public function turnIntoDormantAction()
    {
        // 휴면 계정인지 검사한 user 데이터 가져오기
        $userRow = Dormant::getDormantUser();
        $mailType = "휴면 전환 알림 & 30일 뒤 개인정보파기 알림";
        $count = 0;
        foreach ($userRow as $row) {
            if($row['mem_dor_mail'] === 'Y') { // 이전 휴면 예정 메일을 받은 적이 있다면
                // 메일 보내기
                $mailResult = DormantNotice::mail($row['mem_email'], $row['mem_user_id']);
                $logResult = Membership::emailSendLog($row, $mailType);
                if(!$logResult || !$mailResult) {
                    // 메일 전송 또는 로그 저장에 문제가 발생한 경우
                    View::render('Error/errorPage.php', [
                        'alert' => "이메일 전송에 실패했습니다. 다시 시도해주세요.",
                        'back' => "true"
                    ]);
                    exit;
                }
                // 메일 보낸 후 휴면 계정으로 전환
                $row['mem_status'] = 'H';
                $dormantType = "IN";
                $insertResult = Dormant::insertDormantTable($row);              // 휴면 계정 Table insert
                $logDorResult = Dormant::logDormantTable($row, $dormantType);   // 로그 남기기
                $deleteResult = Dormant::deleteUserData($row);                  // 기존 유저 Table delete
                if(!$logDorResult || !$insertResult || !$deleteResult) {
                    // 휴면 계정 전환 과정에 문제가 발생한 경우
                    View::render('Error/errorPage.php', [
                        'alert' => "휴면 계정 전환 오류가 발생했습니다. 다시 시도해주세요.",
                        'back' => "true"
                    ]);
                    exit;
                }
                $count = $count + 1;
            }
        }
        echo "<script> alert('휴면 계정 ['+$count+'] 개 전환이 완료되었습니다.'); history.back();</script>";
    }

    /**
     * (휴면 상태로 로그인) 계정 복구 인증 메일 전송
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

    /** (인증 완료) 휴면 해제 -> DB data 수정 */
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
                $dormantType = "OUT";
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
        View::render('Login/dormantOK.html');
    }

    /**
     * (60일 미접속) 휴면 계정 파기(삭제)
     */
    public function deleteDormantAction()
    {
        // 60일 미접속 휴면 계정 데이터 가져오기
        $userRow = Dormant::getDestroyDormantUser();
        $deleteType = "D"; // 휴면 연장으로 삭제
        $count = 0;
        foreach ($userRow as $row) {
            $row['reason_detail'] = "휴면 계정법에 따른 파기";
            $row['mem_status'] = 'N';
            $insertResult = Dormant::insertWithdraw($row, $deleteType); // 탈퇴 계정 Table insert
            $stateChange  = Dormant::stateToDelete($row);                // 유저 탈퇴 상태로 변경
            $deleteResult = Dormant::destroyDormantUser($row);          // 휴면 계정 Table delete

            if (!$insertResult || !$deleteResult || !$stateChange) {
                // 휴면 계정 파기 과정에서 문제가 발생
                View::render('Error/errorPage.php', [
                    'alert' => "휴면 계정 전환 오류가 발생했습니다. 다시 시도해주세요.",
                    'back' => "true"
                ]);
                exit;
            }
            $count = $count + 1;
        }
        echo "<script> alert('휴면 계정 ['+$count+'] 개 삭제 완료되었습니다.'); history.back();</script>";
    }
}