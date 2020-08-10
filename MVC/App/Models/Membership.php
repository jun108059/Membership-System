<?php

namespace App\Models;

use DateTime;
use PDO;
use PDOException;

/**
 * Membership model
 */
class Membership extends \Core\Model
{
    /**
     * 회원 가입을 통해 받은 유저 정보 저장
     * @param $user // 가입 유저 정보
     * @return bool $user != 배열 || Null = False
     */
    public static function insertInfo($user)
    {
        // $user = 배열 && !Null 검사
        if (empty($user) || !is_array($user)) {
            return false;
        }
        // DB 연결 > 추상화 Core Model 클래스 - getDB() 호출
        $db = static::getDB();

        $bindArray = [
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
        // SQL Injection 방지 (placeHolder)
        $stmt = $db->prepare("INSERT INTO user SET
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
        ");
        // binding 값 넘겨서 실행
        return $stmt->execute($bindArray);
    }

    /**
     * 중복 Id 있는지 검사
     * @param $userID
     * @return bool
     */
    public static function  isUserExisted($userID)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_user_id from user WHERE mem_user_id=:userID");
        $stmt->bindValue(':userID', $userID, PDO::PARAM_STR);
        $stmt->execute();

        // true or false 만 반환
        return $stmt->rowCount() > 0;
    }

    /**
     * 중복 Email 있는지 검사
     * @param $email
     * @return bool
     */
    public static function isEmailExisted($email)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_email from user WHERE mem_email=:email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        if (empty($row['mem_email'])) {
            // 휴면계정 일 경우
            $stmt2 = $db->prepare("SELECT mem_email FROM dormant WHERE mem_email=:email");
            $stmt2->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt2->execute();
            return $stmt2->rowCount() > 0;
        }else {
            return $stmt->rowCount() > 0;
        }
    }

    /**
     * 중복 Phone 있는지 검사
     * @param $phone
     * @return bool
     */
    public static function isPhoneExisted($phone)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_phone from user WHERE mem_phone=:phone");
        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * ID 찾기
     * @param $email
     * @return string ID 반환
     */
    public static function findId($email)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_user_id from user WHERE mem_email=:email");
        $stmt->bindValue(':email', $email,PDO::PARAM_STR);
        // PDO Statement 객체가 가진 쿼리 실행
        $stmt->execute();
        if($stmt->rowCount() === 0) {
            $stmt2 = $db->prepare("SELECT mem_user_id FROM dormant WHERE mem_email=:email");
            $stmt2->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt2->execute();
            $row2 = $stmt2 ->fetch();
            // DB ID 반환
            return $row2['mem_user_id'];
        }else {
            // 결과 값 가져 오기
            $row = $stmt->fetch();
            // DB ID 반환
            return $row['mem_user_id'];
        }
    }

    /**
     * 이름 <-> Email 일치 확인
     * @param $email
     * @param $name
     * @return bool 일치 여부 반환
     */
    public static function checkNameEmailRight($name, $email)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_name from user WHERE mem_email=:email");
        $stmt->bindValue(':email', $email,PDO::PARAM_STR);
        // PDO Statement 객체가 가진 쿼리 실행
        $stmt->execute();
        // DB 참조 결과 없음 = 휴면 계정
        if($stmt->rowCount() === 0) {
            $stmt2 = $db->prepare("SELECT mem_name FROM dormant WHERE mem_email=:email");
            $stmt2->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt2->execute();
            $row2 = $stmt2 ->fetch();
            // DB name 과 입력된 name 일치 여부 반환 (T/F)
            return $row2['mem_name']===$name;
        }else {
            // 결과 값 가져 오기
            $row = $stmt->fetch();
            return $row['mem_name'] === $name;
            // DB name 과 입력된 name 일치 여부 반환 (T/F)

        }
    }

    /**
     * 재설정 비밀번호 DB Update
     * @param $userData
     * @return bool
     */
    public static function changePassword($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $bindArray = [
            'password'  => $userData['mem_password'],
            'dateTime'  => $userData['mem_pw_dt'],
            'id'        => $userData['mem_user_id']
        ];

        $sql = "UPDATE user SET 
                mem_password = :password, 
                mem_pw_dt    = :dateTime 
                WHERE mem_user_id = :id";
        $stmt = $db->prepare($sql);
        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);
        return true;
        // 에러 처리 필요
    }

    /**
     * 개인정보 수정 DB Update
     * @param $userData
     * @return bool
     */
    public static function changeInfo($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $bindArray = [
            'password'      => $userData['mem_password'],
            'name'          => $userData['mem_name'],
            'phone'         => $userData['mem_phone'],
            'gender'        => $userData['mem_gender'],
            'pwDateTime'    => $userData['mem_pw_dt'],
            'logDateTime'   => $userData['mem_log_dt'],
            'id'            => $userData['mem_user_id']
        ];

        $sql = "UPDATE user SET 
                mem_password    = :password, 
                mem_name        = :name, 
                mem_phone       = :phone, 
                mem_gender      = :gender, 
                mem_pw_dt       = :pwDateTime,
                mem_log_dt      = :logDateTime
                WHERE mem_user_id = :id";
        $stmt = $db->prepare($sql);
        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);
        return true;
        // 에러 처리 필요
    }

    /**
     * (회원탈퇴) Delete 회원 정보
     * @param $userData // 가입 유저 정보
     * @return bool $user != 배열 || Null = False
     */
    public static function deleteInfo($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // DB 연결 > 추상화 Core Model 클래스 - getDB() 호출
        $db = static::getDB();

        $user_id = $userData['mem_user_id']; // 탈퇴할 user id
        $user_log = $userData['mem_log_dt']; // 탈퇴 일자
        $reason = $userData['reason_detail']; // 탈퇴 사유

        // withdraw 탈퇴한 계정 테이블 Insert

        // mem_idx 가져오기
        $stmt = $db->prepare("SELECT mem_idx, mem_reg_dt from user WHERE mem_user_id=:userID");
        $stmt->bindValue(':userID', $user_id, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt ->fetch();

        // user 테이블에서 가져온 값 탈퇴 테이블에 저장하기 위해 bind
        $bindArray = [
            'mem_idx'       => $row['mem_idx'],
            'id'            => $user_id,
            'reg_date'      => $row['mem_reg_dt'],
            'withdraw_date' => $user_log,
            'reason'        => 'S',
            'reason_detail' => $reason
        ];

        // SQL Injection 방지 (placeHolder)
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

        // user 테이블 삭제하는 코드
        $sql = "DELETE FROM user WHERE mem_user_id = :userID";
        $stmt = $db->prepare($sql);
        $bindArray = [
            'userID' => $user_id,
        ];
        $stmt->execute($bindArray);
        return true;
    }

    /**
     * (휴면계정) 이메일 전송 기록 남기기
     * @param $userData
     * @param $type
     * @return bool
     */
    public static function emailSendLog($userData, $type)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }
        // DB 연결
        $db = static::getDB();
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $bindArray = [
            'mem_idx'    => $userData['mem_idx'],
            'id'         => $userData['mem_user_id'],
            'send_log'   => $now,
            'err_check'  => 'F',
            'err_detail' => 'None',
            'email_type' => $type
        ];
        // 이메일 전송 테이블 Insert 쿼리
        $sql = "INSERT INTO email_send_log SET
                     mem_idx        = :mem_idx,
                     id             = :id,
                     send_log       = :send_log,
                     err_check      = :err_check,
                     err_detail     = :err_detail,
                     email_type     = :email_type
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute($bindArray);
    }


    /**
     * 개인 정보 수정 - 현재 Password 일치 여부 검사
     * @param $userId
     * @return bool
     */
    public static function checkPassword($userId)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_password from user WHERE mem_user_id=:userID");
        $stmt->bindValue(':userID', $userId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

}
