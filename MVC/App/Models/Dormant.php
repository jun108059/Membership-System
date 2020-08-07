<?php

namespace App\Models;

use DateTime;
use PDO;

/**
 * Dormant model
 */
class Dormant extends \Core\Model
{
    /**
     * (휴면 전환) 전환 예정 회원 추출 (15일 이상 미접속)
     * @return array 연관 배열
     */
    public static function getSoonDormantUser() {
        $db = static::getDB();
        // 15일 이상 접속 안한 유저 (30일 이후에 전환 예정을 알리기 위해)
        $sql = "SELECT * FROM user WHERE (datediff(now(), mem_log_dt)>15)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * (휴면 전환) 전환 회원 추출 (30일 이상 미접속)
     * @return array 연관 배열
     */
    public static function getDormantUser() {
        $db = static::getDB();
        // 30일 이상 접속 안한 유저 전환
        $sql = "SELECT * FROM user WHERE (datediff(now(), mem_log_dt)>30)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * (휴면 전환 1) 휴면 메일 보낸 상태로 만들기
     * @param $userData
     * @return bool
     */
    public static function noticeMailSendStatus($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }
        $db = static::getDB();
        // 1. 휴면 전환 메일 전송 여부 업데이트 (user 테이블)
        $bindArray = [
            'email'         => $userData['mem_email'],
            'dormantMail'   => $userData['mem_dor_mail'],
        ];

        $sql = "UPDATE user SET 
                mem_dor_mail    = :dormantMail
                WHERE mem_email = :email";

        $stmt = $db->prepare($sql);
        $stmt->execute($bindArray);
        return true;
    }

    /**
     * (휴면 전환 2) 30일 미접속 - 휴면 Table 에 Insert
     * @param $user
     * @return bool
     */
    public static function insertDormantTable($user)
    {
        // $user = 배열 && !Null 검사
        if (empty($user) || !is_array($user)) {
            return false;
        }
        $db = static::getDB();
        // 휴면 Table 에 정보 복사
        $bindArray = [
            'mem_idx'       => $user['mem_idx'],
            'mem_user_id'   => $user['mem_user_id'],
            'mem_email'     => $user['mem_email'],
            'mem_password'  => $user['mem_password'],
            'mem_status'    => $user['mem_status'],
            'mem_dor_mail'  => $user['mem_dor_mail'],
            'mem_name'      => $user['mem_name'],
            'mem_phone'     => $user['mem_phone'],
            'mem_gender'    => $user['mem_gender'],
            'mem_level'     => $user['mem_level'],
            'mem_reg_dt'    => $user['mem_reg_dt'],
            'mem_log_dt'    => $user['mem_log_dt'],
            'mem_pw_dt'     => $user['mem_pw_dt']
        ];

        $sql = "INSERT INTO dormant SET
                     mem_idx        = :mem_idx,
                     mem_user_id    = :mem_user_id,
                     mem_email      = :mem_email,
                     mem_password   = :mem_password,
                     mem_status     = :mem_status,
                     mem_dor_mail   = :mem_dor_mail,
                     mem_name       = :mem_name,
                     mem_phone      = :mem_phone,
                     mem_gender     = :mem_gender,
                     mem_level      = :mem_level,
                     mem_reg_dt     = :mem_reg_dt,
                     mem_log_dt     = :mem_log_dt,
                     mem_pw_dt      = :mem_pw_dt
               ";

        $stmt = $db->prepare($sql);
        $stmt->execute($bindArray);
        return true;
    }

    /**
     * (휴면 전환 3) 기존 유저 테이블 정보 날리기
     * @param $user
     * @return bool
     */
    public static function deleteUserData($user)
    {
        // $user = 배열 && !Null 검사
        if (empty($user) || !is_array($user)) {
            return false;
        }
        $db = static::getDB();
        // 유지할 정보만 남기기
        $bindArray = [
            'mem_user_id'   => $user['mem_user_id'],
            'mem_email'     => Null,
            'mem_password'  => $user['mem_password'],
            'mem_status'    => $user['mem_status'],
            'mem_dor_mail'  => Null,
            'mem_name'      => Null,
            'mem_phone'     => Null,
            'mem_gender'    => Null,
            'mem_level'     => Null,
            'mem_reg_dt'    => Null,
            'mem_log_dt'    => Null,
            'mem_pw_dt'     => Null,
        ];

        $sql = "UPDATE user SET 
                     mem_email      = :mem_email,
                     mem_password   = :mem_password,
                     mem_status     = :mem_status,
                     mem_dor_mail   = :mem_dor_mail,
                     mem_name       = :mem_name,
                     mem_phone      = :mem_phone,
                     mem_gender     = :mem_gender,
                     mem_level      = :mem_level,
                     mem_reg_dt     = :mem_reg_dt,
                     mem_log_dt     = :mem_log_dt,
                     mem_pw_dt      = :mem_pw_dt
                WHERE mem_user_id = :mem_user_id";

        $stmt = $db->prepare($sql);
        $stmt->execute($bindArray);

        return true;
    }


    /**
     * (휴면 해제 1) 회원 Table 복구 Update
     * @param $userData
     * @return bool
     */
    public static function releaseDormant($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // DB 연결
        $db = static::getDB();

        $bindArray = [
            'idx'           => $userData['mem_idx'],
            'email'         => $userData['mem_email'],
            'password'      => $userData['mem_password'],
            'status'        => $userData['mem_status'],
            'dormantMail'   => $userData['mem_dor_mail'],
            'name'          => $userData['mem_name'],
            'phone'         => $userData['mem_phone'],
            'gender'        => $userData['mem_gender'],
            'level'         => $userData['mem_level'],
            'regDateTime'   => $userData['mem_reg_dt'],
            'logDateTime'   => $userData['mem_log_dt'],
            'pwDateTime'    => $userData['mem_pw_dt'],
        ];
        // 회원 정보 복구
        $sql = "UPDATE user SET 
                mem_email       = :email, 
                mem_password    = :password, 
                mem_status      = :status,
                mem_dor_mail    = :dormantMail, 
                mem_name        = :name, 
                mem_phone       = :phone, 
                mem_gender      = :gender, 
                mem_level       = :level, 
                mem_reg_dt      = :regDateTime,
                mem_log_dt      = :logDateTime,
                mem_pw_dt       = :pwDateTime
                WHERE mem_idx   = :idx";

        $stmt = $db->prepare($sql);
        $stmt->execute($bindArray);

        return true;

    }

    /**
     * (휴면 해제 2) 휴면 Table 회원 Delete
     * @param $userData // 가입 유저 정보
     * @return bool $user != 배열 || Null = False
     */
    public static function deleteDormant($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        $db = static::getDB();
        $user_id = $userData['mem_user_id']; // 탈퇴할 user id
        // user 테이블 삭제하는 코드
        $sql = "DELETE FROM dormant WHERE mem_user_id = :userID";

        $stmt = $db->prepare($sql);
        $bindArray = [
            'userID' => $user_id,
        ];
        $stmt->execute($bindArray);

        return true;
    }

    /**
     * (휴면 전환&복구) 로그 테이블 업데이트
     * @param $userData
     * @param $dormantType
     * @return bool
     */
    public static function logDormantTable($userData, $dormantType)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // DB 연결
        $db = static::getDB();

        // 휴면 전환 로그 binding Value
        $bindArray = [
            'mem_idx'    => $userData['mem_idx'],
            'id'         => $userData['mem_user_id'],
            'dormant_status' => $dormantType,
            'dormant_dt' => (new DateTime())->format('Y-m-d H:i:s'),
        ];

        // 휴면 전환 로그 테이블 INSERT
        $sql = "INSERT INTO dormant_log SET
                     mem_idx        = :mem_idx,
                     id             = :id,
                     dormant_status = :dormant_status,
                     dormant_dt     = :dormant_dt
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute($bindArray);

        return true;
    }

    /**
     * 유저 정보 추출
     * @param $email
     * @return array
     */
    public static function getUserInfo($email)
    {
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT * from dormant WHERE mem_email=:email");
        $stmt->bindValue(':email', $email,PDO::PARAM_STR);
        // PDO Statement 객체가 가진 쿼리 실행
        $stmt->execute();
        // 결과 값 가져 오기
        return $stmt ->fetch();
    }

    /**
     * (파기될 User 목록) User 추출 (60일 이상 미접속)
     * @return array 연관 배열
     */
    public static function getDestroyDormantUser() {
        $db = static::getDB();
        // 60일 이상 접속 안한 유저 전환
        $sql = "SELECT * FROM dormant WHERE (datediff(now(), mem_log_dt)>60)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * 유저 삭제 전 탈퇴 테이블에 삽입 (호출 전 mem_log_dt 최신화 확인)
     * @param $userData
     * @param $deleteType
     * @return bool
     */
    public static function insertWithdraw($userData, $deleteType) {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }
        $db = static::getDB();

        // withdraw 탈퇴한 계정 테이블 Insert
        $bindArray = [
            'mem_idx'       => $userData['mem_idx'],
            'id'            => $userData['mem_user_id'],
            'reg_date'      => $userData['mem_reg_dt'],
            'withdraw_date' => (new DateTime())->format('Y-m-d H:i:s'),
            'reason'        => $deleteType,
            'reason_detail' => $userData['reason_detail']
        ];
        $stmt = $db->prepare("INSERT INTO withdraw SET
                     mem_idx        = :mem_idx,
                     id             = :id,
                     reg_date       = :reg_date,
                     withdraw_date  = :withdraw_date,
                     reason         = :reason,
                     reason_detail  = :reason_detail
        ");
        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);
        return true;
    }

    /**
     * 유저 정보 탈퇴로 변경
     * @param $userData
     * @return bool
     */
    public static function stateToDelete($userData) {
        if (empty($userData) || !is_array($userData)) {
            return false;
        }
        $db = static::getDB();

        $bindArray = [
            'status'    => $userData['mem_status'],
            'mem_idx'   => $userData['mem_idx'],
        ];
        // 상태 변경
        $sql = "UPDATE user SET 
                mem_status    = :status
                WHERE mem_idx = :mem_idx";

        $stmt = $db->prepare($sql);
        $stmt->execute($bindArray);

        return true;
    }


    /**
     * 휴면 유저 삭제
     * @param $userData
     * @return bool
     */
    public static function destroyDormantUser($userData) {
        if (empty($userData) || !is_array($userData)) {
            return false;
        }
        $db = static::getDB();
        // 휴면 테이블 삭제하는 코드
        $sql = "DELETE FROM dormant WHERE mem_user_id = :userID";
        $stmt = $db->prepare($sql);
        $bindArray = [
            'userID' => $userData['mem_user_id'],
        ];
        $stmt->execute($bindArray);
        return true;
    }
}
