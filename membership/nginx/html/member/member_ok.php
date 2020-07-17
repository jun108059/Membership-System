<?php
include "../db.php";
include "../password.php";

$now = (new \DateTime())->format('Y-m-d H:i:s');

$mem_user_id = $_POST['id'];
$mem_email = $_POST['email'] . '@' . $_POST['emadress'];
$mem_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$mem_status = 'Y'; // enum 타입 - 정상 가입 이면 status 설정 고민
$mem_cert = 'N'; // enum 타입 - 본인 인증 여부 가져오기!!
$mem_name = $_POST['name'];
$mem_phone = $_POST['phone'];
$mem_gender = $_POST['gender']; // enum 타입
$mem_level = 4; // 일반 사용자 level 4
$mem_reg_dt = $now; // 회원가입 일시
$mem_log_dt = $now; // 마지막 로그인 일시
$mem_pw_dt = $now; // 마지막 비밀번호 변경 일시


$sql = mq("
    INSERT INTO user(
        mem_user_id, mem_email, mem_password, mem_status, mem_cert, mem_name,
        mem_phone, mem_gender, mem_level, mem_reg_dt, mem_log_dt, mem_pw_dt
    ) VALUES (
        '" . $mem_user_id . "', 
        '" . $mem_email . "', 
        '" . $mem_password . "',
        '" . $mem_status . "',
        '" . $mem_cert . "', 
        '" . $mem_name . "',
        '" . $mem_phone . "',
        '" . $mem_gender . "',
        '" . $mem_level . "',
        '" . $mem_reg_dt . "',
        '" . $mem_log_dt . "',
        '" . $mem_pw_dt . "'
    )"
);
echo $sql;

?>
<meta charset="utf-8"/>
<script type="text/javascript">alert('회원가입이 완료되었습니다.');</script>
<meta http-equiv="refresh" content="0 url=/index.php">