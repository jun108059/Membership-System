<?php
    $host = 'localhost';
    $user = 'root';
    $pw = '1234';
    $dbName = 'information_schema';
    $mysqli = new mysqli($host, $user, $pw, $dbName);
    if($mysqli -> connect_errno) {
        echo "MySQL 연결 오류";
    } else {
        echo "드디어 MySQL 연결 성공";
    }
    mysqli_close($mysqli);
?>