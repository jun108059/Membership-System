<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8"/>
    <title>회원가입 완료</title>
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
</head>
<body>
<br>
<h1>
    <div style="text-align: center;">🎉회원가입을 축하드립니다!🎉</div>
</h1>
<h2 align="center"> 📢 안녕하세요 <?php echo $name?> 님</h2>
<h3 align="center"> ID = <?php echo $id?><br><br> Email = <?php echo $email?><br><br>Home 으로 이동합니다.</h3>
<p align="center" class="countdown"></p>
<br>

<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
<script>
    // 카운트 시작 숫자
    var count = 5;
    // 카운트 다운 함수
    var countdown = setInterval(function(){
        // 해당 태그에 아래 내용을 출력
        $("p.countdown").html("<b><font color='blue' size='66pt'>"
            + count + "</font><b><p>초 후 이동 합니다.</p>");
        // 0초면 초기화 후 이동 되는 사이트
        if (count === 0) {
            clearInterval(countdown);
            window.open("/", "_self");
        }
        count--;//카운트 감소
    }, 1000);
</script>
</body>
</html>