<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8"/>
    <title>비밀번호 변경</title>
    <style>
        * {margin: 0 auto;}
        a {color:#333; text-decoration: none;}
        .change {text-align:center; width:900px; margin-top:30px; }
    </style>
</head>
<body>
<div class="change">
    <form id="myForm" action="/Membership/newPwToDB" method="post" >
        <h1>🔒Password를 변경하세요!🔒</h1>
        <br>
        <input type="hidden" name="user_id" id="user_id" value="<?php echo($user_id)?>">
        <input type="hidden" name="user_pw" id="user_pw" value="<?php echo($user_pw)?>">

        <h3>[<?php echo htmlspecialchars($user_id);?>] 님 안녕하세요.</h3>
        <br>
        <fieldset>
            <legend>비밀번호 변경</legend>
            <table>
                <tr>
                    <td style="color: mediumblue; font-weight: bold;">현재 비밀번호<br><br></td>
                    <td><input type="password" size="35" name="password" placeholder="사용중인 비밀번호"
                               class="checkPw" id="curr_pw" maxlength="20" required><br><br></td>
                    <td><div>&nbsp;현재 비밀번호를 입력하세요.</div><br></td>

                <tr>
                <tr>
                    <td>비밀번호<br><br></td>
                    <td><input type="password" size="35" name="password" placeholder="비밀번호(8 ~ 20 글자)"
                               class="memberPw" id="pw1" maxlength="20" required><br><br></td>
                    <td><div id="check-pw-success" style="display: none; color: limegreen; font-weight: bold;">&nbsp;🟢사용가능한 비밀번호🟢<br><br></div>
                        <div id="check-pw-fail" style="display: inline; color: orange; font-weight: bold;">&nbsp;❌8~20자리로 영문, 숫자 포함❌<br><br></div></td>
                    <td><div id="alert-success" style="display: none; color: blue; font-weight: bold;">&nbsp;<br/>✔😄비밀번호 일치😄✔</div>
                        <div id="alert-danger" style="display: inline; color: red; font-weight: bold;">&nbsp;<br/>❗비밀번호가 다름❗</div></td>

                </tr>
                <tr>
                    <td>비밀번호 확인<br><br></td>
                    <td><input type="password" size="35" name="password2" placeholder="비밀번호 확인"
                               class="memberPw2" id="pw2" maxlength="20" required ><br><br></td>

                </tr>
            </table>
            <input type="submit" value="변경하기" />
        </fieldset>
    </form>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // 실시간 변화 감지
        $(".checkPw").on("keyup", function(e){ // check 라는 클래스에 입력을 감지
            let user_pw = $("#user_pw").val(); // 기존 password
            let curr_pw = $("#curr_pw").val(); // 입력한 password

            $self = $(this);
            var $user_id = $("#user_id").val();
            $.post( //post 방식으로 user id 값 넘기기
                "/Membership/checkPw",
                { currPw : $(this).val(), userId : $user_id },
                function(data){
                    if(data){ //만약 data 값이 전송 되면
                        $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        $self.parent().parent().find("div").css("color", "#F00"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                    }
                }
            );
        });
    });

    // 비밀 번호 글자 수 유효성 검사
    $(".memberPw").on("keyup", function () {
        let passwordReg = /^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/;
        if( !passwordReg.test($("#pw1").val()) ) {
            $("#check-pw-fail").css('display', 'inline');
            $("#check-pw-success").css('display', 'none');
        } else {
            $("#check-pw-fail").css('display', 'none');
            $("#check-pw-success").css('display', 'inline');
        }
    });

    // 비밀 번호 일치 검사
    $(".memberPw2").on("keyup", function () {
        let pwd1 = $("#pw1").val();
        let pwd2 = $("#pw2").val();

        // Null 확인
        if ( pwd1 && !pwd2 ) {
            null;
        } else if (pwd1 || pwd2) {
            if (pwd1 === pwd2) {
                $("#alert-success").css('display', 'inline');
                $("#alert-danger").css('display', 'none');
            } else {
                $("#alert-success").css('display', 'none');
                $("#alert-danger").css('display', 'inline');
            }
        }
    });
</script>

</body>
</html>