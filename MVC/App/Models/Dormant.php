<?php

namespace App\Models;

use DateTime;
use PDO;
use PDOException;

/**
 * Dormant model
 */
class Dormant extends \Core\Model
{

    /**
     * (휴면 전환) 전환 회원 추출
     * @return array 연관 배열
     */
    public static function getDormantUser() {
        $db = static::getDB();

        // 필요에 따라 date_add

        // date diff <-> datetime 데이터 타입에 맞는지 확인

        // 9일 이상 접속 안한 유저
        $sql = "SELECT * FROM user WHERE (datediff(now(), mem_log_dt)>9)";

        $stmt = $db->prepare($sql);

        // binding 값 넘겨서 실행
        $stmt->execute();

//        $row = $stmt->fetchAll();

        return $stmt->fetchAll();

//        $row = $stmt->fetch();
//        print_r($row);
    }

    /**
     * (휴면 전환) user DB Update
     * @param $userData
     * @return bool
     */
    public static function convertDormant($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // DB 연결
        $db = static::getDB();

        $bindArray = [
            'email'         => $userData['mem_email'],
            'dormantMail'   => $userData['mem_dor_mail'],
            'logDateTime'   => $userData['mem_log_dt'],
            'status'        => $userData['mem_status']
        ];

        $sql = "UPDATE user SET 
                mem_dor_mail    = :dormantMail, 
                mem_log_dt      = :logDateTime,
                mem_status      = :status
                WHERE mem_email = :email";

        $stmt = $db->prepare($sql);

        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);


        // 휴면 전환 로그 binding Value
        $bindArray = [
            'mem_idx'    => $userData['mem_idx'],
            'id'         => $userData['mem_user_id'],
            'dormant_status' => 'OUT',
            'dormant_dt' => $userData['mem_log_dt']
        ];

        // 휴면 전환 로그 테이블 INSERT
        $sql = "INSERT INTO dormant_log SET
                     mem_idx        = :mem_idx,
                     id             = :id,
                     dormant_status = :dormant_status,
                     dormant_dt     = :dormant_dt
        ";
        $stmt = $db->prepare($sql);
        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);

        return true;
        // 에러 처리 필요
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
            'name'        => $userData['mem_name'],
            'phone'        => $userData['mem_phone'],
            'gender'        => $userData['mem_gender'],
            'level'        => $userData['mem_level'],
            'regDateTime'   => $userData['mem_reg_dt'],
            'logDateTime'   => $userData['mem_log_dt'],
            'pwDateTime'   => $userData['mem_pw_dt'],
        ];
        // 회원 정보 복구
        $sql = "UPDATE user SET 
                mem_email    = :email, 
                mem_password    = :password, 
                mem_status      = :status,
                mem_dor_mail    = :dormantMail, 
                mem_name    = :name, 
                mem_phone    = :phone, 
                mem_gender    = :gender, 
                mem_level    = :level, 
                mem_reg_dt      = :regDateTime,
                mem_log_dt      = :logDateTime,
                mem_pw_dt      = :pwDateTime
                WHERE mem_idx = :idx";

        $stmt = $db->prepare($sql);
        // binding 값 넘겨서 실행
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

        // DB 연결 > 추상화 Core Model 클래스 - getDB() 호출
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
            'dormant_dt' => $userData['mem_log_dt']
        ];

        // 휴면 전환 로그 테이블 INSERT
        $sql = "INSERT INTO dormant_log SET
                     mem_idx        = :mem_idx,
                     id             = :id,
                     dormant_status = :dormant_status,
                     dormant_dt     = :dormant_dt
        ";
        $stmt = $db->prepare($sql);
        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);

        return true;
        // 에러 처리 필요
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
}
