<?php

namespace App\Models;

use PDO;

/**
 * Admin model
 */
class Admin extends \Core\Model
{

    public static function getUserData()
    {
        // DB 연결 - Abstract Core Model 클래스 - getDB() 호출
        $db = static::getDB();
        // 쿼리를 담은 PDOStatement 객체 생성 - return PDOStatement 객체
        $stmt = $db->prepare("SELECT * FROM user ORDER BY mem_idx");

        // PDOStatement 객체가 가진 쿼리를 실행
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * paging 하기 위한 총 user count
     * @return int 총 user 수
     */
    public static function getAllPageNumber()
    {

        // DB 연결 - Abstract Core Model 클래스 - getDB() 호출
        $db = static::getDB();
        // 쿼리를 담은 PDOStatement 객체 생성 - return PDOStatement 객체
        $stmt = $db->prepare("SELECT mem_idx FROM user");
        // PDOStatement 객체가 가진 쿼리를 실행
        $stmt->execute();
        // 총 User 수 반환
        return $stmt->rowCount();
    }

    /**
     * SELECT * LIMIT 게시판 형태 사용자 정보 가져오기
     *
     * @param $startPoint
     * @param $list
     * @return array
     */
    public static function getPageUserData($startPoint, $list)
    {
        // DB 연결 - Abstract Core Model 클래스 - getDB() 호출
        $db = static::getDB();
        // 쿼리를 담은 PDOStatement 객체 생성 - return PDOStatement 객체
        $stmt = $db->prepare("SELECT * FROM user ORDER BY mem_idx LIMIT $startPoint, $list");

        // PDOStatement 객체가 가진 쿼리를 실행
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * 수정할 User 정보 가져오기
     * @param $userId
     * @return mixed
     */
    public static function editUserData($userId)
    {
        // DB 연결 - Abstract Core Model 클래스 - getDB() 호출
        $db = static::getDB();
        // 쿼리를 담은 PDOStatement 객체 생성 - return PDOStatement 객체
        $stmt = $db->prepare("SELECT * FROM user WHERE mem_user_id = :user_id");

        // PDOStatement 객체가 가진 쿼리의 parameter 에 변수 값을 바인드
        $stmt->bindValue(":user_id", $userId, PDO::PARAM_STR);

        // PDOStatement 객체가 가진 쿼리를 실행
        $stmt->execute();

        return $stmt->fetch();
    }


    /**
     * 회원정보 수정 DB Update
     * @param $userData
     * @return bool
     */
    public static function userInfoUpdate($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // DB 연결: 추상화 Core Model 클래스 - getDB() 호출
        $db = static::getDB();

        $bindArray = [
            'password' => $userData['mem_password'],
            'name' => $userData['mem_name'],
            'phone' => $userData['mem_phone'],
            'pwDateTime' => $userData['mem_pw_dt'],
            'id' => $userData['mem_user_id']
        ];

        $sql = "UPDATE user SET 
                mem_password    = :password, 
                mem_name        = :name, 
                mem_phone       = :phone, 
                mem_pw_dt       = :pwDateTime
                WHERE mem_user_id = :id";
        $stmt = $db->prepare($sql);
        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);
        return true;
        // 에러 처리 필요
    }

    /**
     * (강제 회원탈퇴) Delete 회원 정보
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
            'reason'        => 'F',
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
        // binding 값 넘겨서 실행

        $bindArray = [
            'userID' => $user_id,
        ];

        $stmt->execute($bindArray);
        return true;
        // 에러 처리 필요
    }
}
