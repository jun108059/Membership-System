<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>본인 인증</title>
</head>
<body>
<h1>인증번호를 입력해주세요!</h1>
<p>이메일 주소 : <?php echo htmlspecialchars($mail); ?></p>
<p>Test      : <?php echo htmlspecialchars($certify); ?></p>
<p>
<!--    <form >-->
    <strong>인증번호</strong>
    <input type="hidden" name="email" id="email" value="<?php echo($mail)?>">
    <input type="hidden" name="certify" id="certify" value="<?php echo($certify)?>">
    <label for="inputVal"></label><input type="text" name="inputVal" id="inputVal" size="20" placeholder="6자리 숫자" maxlength="6" required>
    <button id="button1" onclick="certifyEmail()">인증하기</button>
<!--</form>-->
</p>
<div id = "time"></div>
<p id = "result"></p>


<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
<script>
    let time = 600; // 기준 시간
    let min = "";  // 분
    let sec = ""; // 초

    //setInterval(함수, 시간) : 주기적 실행
    let x = setInterval(function () {
        //parseInt() : 정수 반환
        min = parseInt(time / 60); // 몫
        sec = time % 60 // 나머지

        document.getElementById("time").innerHTML = min + "분 " + sec + "초";
        time--;

        // 타임 아웃
        if (time < 0) {
            clearInterval(x); // setInterval 종료
            document.getElementById("time").innerHTML = "인증 시간이 초과되었습니다";
        }
    }, 1000);

    function certifyEmail() {
        let inputVal = $("#inputVal").val();
        let certify = $("#certify").val();
        let link = "signUp";
        if(inputVal === certify) {
            alert("✔인증번호 [ " + certify + " ] 일치합니다.✔");
            submit();
            location.href=link;
        }
        else {
            alert("🔴인증번호가 일치하지 않습니다.🔴");
        }
    }

    function submit() {
        let mail = $("#email").val();
        // 새로운 ELEMENT (FORM)
        let form = document.createElement("form");
        // input 속성 set
        form.name = "form";
        form.method = "POST"; //Post 방식
        form.action = "/Membership/signUp"; //요청 보낼 주소
        form.target = "_blank";
        form.acceptCharset = "UTF-8";
        // ELEMENT 중 input 생성
        let hiddenField = document.createElement("input");
        // input data 값
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "email");
        hiddenField.setAttribute("value", mail);

        // (form 으로) input 넣기
        form.appendChild(hiddenField);

        //하나 더 TEST 할 때는 똑같이 추가
        //Form 을 body 에 추가
        document.body.appendChild(form);

        // form submit
        form.submit();
    }

</script>

</body>
</html>