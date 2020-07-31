<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
<h1>멤버쉽 시스템 Home</h1>
<!--<p>안녕하세요! <span id="name"></span> 님 멤버쉽 서비스입니다.</p>-->
<p>hi ! <?php echo "<h2> {$_SESSION['userID']} 님 환영합니다😄</h2>"?></p>
<br><br>
<a href="/Home/infoModify"><input type="button" value="개인정보수정" /></a>
<a href="/Login/logout"><input type="button" value="로그아웃" /></a>
<!--<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>-->
<!--<script>-->
<!--    $.ajax({-->
<!--        url: "/Home/myName",-->
<!--        type: "GET",-->
<!--    }).done(function(data) {-->
<!--        $('#name').text(data);-->
<!--    });-->
<!--</script>-->
</body>
</html>

