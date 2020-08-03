<?php
namespace App\Service;

use DateTime;
use PDO;
use PDOException;

class DormantCheck extends \Core\Model
{
    public static function getDormantUser() {
        $db = static::getDB();

        // 필요에 따라 date_add

        // date diff <-> datetime 데이터 타입에 맞는지 확인
        // 3일 이상 접속 안한 유저
        $sql = "SELECT mem_user_id FROM user WHERE (datediff(now(), mem_log_dt)>3)";

        $stmt = $db->prepare($sql);

        // binding 값 넘겨서 실행
        $stmt->execute();
        $row = $stmt->fetch();

        print_r($row);
    }



}