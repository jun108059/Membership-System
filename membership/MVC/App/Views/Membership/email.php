<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>본인 인증</title>
</head>
<body>
<h1>인증번호를 입력해주세요!</h1>
<p>이메일 주소 : <?php echo htmlspecialchars($mail); ?></p>
<form method="post">
    <p>
        <strong>인증번호</strong>
        <input type="number" size="20" name="certify" placeholder="6자리 숫자" maxlength="6" required>
    </p>
    <p>
        <input type="submit" value="인증하기" formaction="/Membership/signUpDB"/>
    </p>
</form>
<div id = "time"></div>
</body>
</html>

<script>
    var time = 600; // 기준 시간
    var min = "";  // 분
    var sec = ""; // 초

    //setInterval(함수, 시간) : 주기적 실행
    var x = setInterval(function() {
        //parseInt() : 정수 반환
        min = parseInt(time/60); // 몫
        sec = time%60 // 나머지

        document.getElementById("time").innerHTML = min + "분 " + sec + "초";
        time--;

        // 타임 아웃
        if (time < 0) {
            clearInterval(x); // setInterval 종료
            document.getElementById("time").innerHTML = "인증 시간이 초과되었습니다";
        }
    }, 1000);
</script>
