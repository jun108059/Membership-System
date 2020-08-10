<?php
if(!isset($_SESSION['userID'])) {
    echo '<script> alert("🧨잘못된 접근입니다.(home/index.php)"); history.back(); </script>';
//    echo "<meta http-equiv='refresh' content='0; url=/'>";
    exit;
}
$_SESSION["userLog"] = (new DateTime())->format('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
<h1>멤버쉽 시스템 Home</h1>
<p><?php echo "<h2> {$_SESSION['userID']} 님 환영합니다😄</h2>"?></p>

<img src="/public/image/saramin.PNG" alt="사람인 HR">

<br><br>
<a href="/Home/infoModify"><input type="button" value="개인정보수정" /></a>
<a href="/Login/logout"><input type="button" value="로그아웃" /></a>
</body>
</html>

