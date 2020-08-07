<?php

namespace App\Models;

use PDO;

/**
 * Admin model
 */
class Admin extends \Core\Model
{

    /**
     * User 정보 가져오기
     * @return array
     */
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
     * (개인정보 상세보기) paging 하기 위한 총 user count
     * @return int 총 user 수
     */
    public static function getAllPageNumber()
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT mem_idx FROM user");
        $stmt->execute();
        // 총 User 수 반환
        return $stmt->rowCount();
    }

    /**
     * (개인정보 상세보기) SELECT * LIMIT 게시판 형태 사용자 정보 가져오기
     *
     * @param $startPoint
     * @param $list
     * @return array
     */
    public static function getPageUserData($startPoint, $list)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM user ORDER BY mem_idx LIMIT $startPoint, $list");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * (user 정보 수정) 수정할 User 정보 가져오기
     * @param $userId
     * @return array
     */
    public static function editUserData($userId)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM user WHERE mem_user_id = :user_id");
        $stmt->bindValue(":user_id", $userId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }


    /**
     * (user 정보 수정) DB Update
     * @param $userData
     * @return bool
     */
    public static function userInfoUpdate($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }
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
        $stmt->execute($bindArray);
        return true;
    }
}
