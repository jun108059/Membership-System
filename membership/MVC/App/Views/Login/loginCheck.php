<?php

echo 'check id / pw';
// POST 로 받아온 id/pw 비어 있다면 알림 창을 띄우고 전 페이지 돌아감
if ($_POST["mem_user_id"] == "" || $_POST["mem_password"] == "") {
    echo '<script> alert("아이디와 패스워드 입력하세요"); history.back(); </script>';
} else {

    // password 변수에 POST로 받아온 값을 저장하고 sql문으로 POST로 받아온 아이디 값을 찾자
    $password = $_POST['mem_password'];
    $sql = mq("select * from user where mem_user_id='" . $_POST['mem_user_id'] . "'");
    $user = $sql->fetch_array();
    $hash_pw = $user['mem_password']; //$hash_pw에 post로 받아온 아이디열의 비밀번호를 저장합니다.

    if (password_verify($password, $hash_pw)) //만약 password변수와 hash_pw변수가 같다면 세션값을 저장하고 알림창을 띄운후 main.php파일로 넘어갑니다.
    {
        $_SESSION['mem_user_id'] = $user["mem_user_id"];
        $_SESSION['mem_password'] = $user["mem_password"];

        echo "<script>alert('로그인되었습니다.'); location.href='/home/index';</script>";
    } else { // 비밀번호가 같지 않다면 알림창을 띄우고 전 페이지로 돌아갑니다
        echo "<script>alert('아이디 또는 비밀번호를 확인하세요.'); history.back();</script>";
    }
}
