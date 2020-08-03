<?php
if (!isset($_SESSION['userID'])) {
    echo '<script> alert("🧨잘못된 접근입니다.(home/index.php)"); history.back(); </script>';
//    echo "<meta http-equiv='refresh' content='0; url=/'>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8"/>
    <title>개인정보수정</title>
</head>
<body>
<form action="/Membership/newInfoToDB" method="post">
    <h1>🔧 개인정보수정 🔧</h1>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo($user_id) ?>">
    <input type="hidden" name="user_pw" id="user_pw" value="<?php echo($user_pw) ?>">
    <input type="hidden" name="user_name" id="user_name" value="<?php echo($user_name) ?>">
    <input type="hidden" name="phone" id="phone" value="<?php echo($phone) ?>">
    <input type="hidden" name="gender" id="gender" value="<?php echo($gender) ?>">
    <input type="hidden" name="register" id="register" value="<?php echo($register) ?>">
    <input type="hidden" name="email" id="email" value="<?php echo($email) ?>">

    <h3>[<?php echo htmlspecialchars($user_id); ?>]님의 개인정보 수정 페이지입니다.</h3>
    <fieldset>
        <legend>개인정보수정</legend>
        <table>
            <tr>
                <td style="color: mediumblue; font-weight: bold;">아이디<br><br></td>
                <td><?php echo($user_id) ?><br><br></td>
            </tr>
            <tr>
                <td style="color: mediumblue; font-weight: bold;">이메일<br><br></td>
                <td><?php echo($email) ?><br><br></td>
            </tr>
            <tr>
                <td style="color: mediumblue; font-weight: bold;">현재 비밀번호<br><br></td>
                <td><input type="password" size="35" name="password" placeholder="사용중인 비밀번호"
                           class="checkPw" id="curr_pw" maxlength="20" required><br><br></td>
                <td>
                    <div>&nbsp;현재 비밀번호를 입력하세요.</div>
                    <br></td>

            <tr>
                <td>변경할 비밀번호<br><br></td>
                <td><input type="password" size="35" name="password" placeholder="비밀번호(8 ~ 20 글자)"
                           class="memberPw" id="pw1" maxlength="20" required><br><br></td>
                <td>
                    <div id="check-pw-success" style="display: none; color: limegreen; font-weight: bold;">&nbsp;🟢사용가능한
                        비밀번호🟢<br><br></div>
                    <div id="check-pw-fail" style="display: inline; color: orange; font-weight: bold;">&nbsp;❌8~20자리로
                        영문, 숫자 포함❌<br><br></div>
                </td>

            </tr>
            <tr>
                <td>비밀번호 확인<br><br></td>
                <td><input type="password" size="35" name="password2" placeholder="비밀번호 확인"
                           class="memberPw2" id="pw2" maxlength="20" required><br><br></td>
                <td>
                    <div id="alert-success" style="display: none; color: blue; font-weight: bold;">&nbsp;✔😄비밀번호
                        일치😄✔<br><br></div>
                    <div id="alert-danger" style="display: inline; color: red; font-weight: bold;">&nbsp;❗비밀번호가
                        다름❗<br><br></div>
                </td>
            </tr>
            <tr>
                <td>이름<br><br></td>
                <td><input type="text" size="35" name="name" placeholder="<?php echo($user_name) ?>"
                           class="memberName" maxlength="20" required><br><br></td>
            </tr>
            <tr>
                <td>전화번호<br><br></td>
                <td><input type="text" size="35" name="phone" placeholder="<?php echo($phone) ?>"
                           id="tell" class="tellCheck" title="010-1234-1234 형식" maxlength="13" required><br><br></td>
                <td>
                    <div>&nbsp;</div>
                    <br></td>
            </tr>
            <tr>
                <td>성별<br><br></td>
                <td>남<input type="radio" name="gender" value="M"> 여<input type="radio" name="gender" value="F" required><br><br>
                </td>
            </tr>
            <tr>
                <td style="color: mediumblue; font-weight: bold;">가입일<br><br></td>
                <td><?php echo($register) ?><br><br></td>
            </tr>

        </table>

        <input type="submit" value="변경하기"/>&nbsp;&nbsp;<input type="reset" value="다시쓰기"/>

    </fieldset>
</form>

<div>
    <br>
    <input type="button" id="back" value="뒤로가기""
    style="color: #fff; background:gray; border-radius:0.5em; padding:3px 10px;"
    />
</div>

<div>
    <br>
    <input type="button" id="withdraw" value="회원탈퇴하기"
           style="color: #fff; background :red; border-radius:0.5em; padding:3px 10px;"
    />
</div>


<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.0.min.js"></script>
</body>
<script>

    function goBack() {
        window.history.back();
    }


    $(document).ready(function () {
        // 실시간 변화 감지
        $(".checkPw").on("keyup", function (e) { // check 라는 클래스에 입력을 감지
            let user_pw = $("#user_pw").val(); // 기존 password
            let curr_pw = $("#curr_pw").val(); // 입력한 password

            $self = $(this);
            var $user_id = $("#user_id").val();
            $.post( //post 방식으로 user id 값 넘기기
                "/Membership/checkPw",
                {currPw: $(this).val(), userId: $user_id},
                function (data) {
                    if (data) { //만약 data 값이 전송 되면
                        $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        $self.parent().parent().find("div").css("color", "#F00"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                    }
                }
            );
        });

        // 비밀 번호 글자 수 유효성 검사
        $(".memberPw").on("keyup", function () {
            let passwordReg = /^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/;
            if (!passwordReg.test($("#pw1").val())) {
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
            if (pwd1 && !pwd2) {
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

        // ID focus out 됐을 때 실행 & 전화 번호 중복 검사
        $(".tellCheck").on("blur", function () {
            // 중복 검사 로직
            let tellReg = /^\d{3}-\d{3,4}-\d{4}$/;
            if (!tellReg.test($(this).val())) {
                alert("❌전화번호 형식으로 입력해주세요.❌");
            }
            //
            $self = $(this);
            $.post( //post 방식으로 user id 값 넘기기
                "/Membership/checkPhone",
                {phone: $(this).val()},
                function (data) {
                    if (data) { //만약 data값이 전송되면
                        $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        $self.parent().parent().find("div").css("color", "#F00"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                    }
                }
            );

        });

        $('#withdraw').click(function () {
            var result = confirm('정말로 탈퇴하시겠습니까?');
            if (result) {
                //yes
                location.replace('/Membership/withDrawPage');
            } else {
                //no - nothing
            }
        });

        $('#back').click(function () {
            var result = confirm('이전 페이지로 이동할까요?');
            if (result) {
                //yes
                history.back();
            } else {
                //no - nothing
            }
        });

    });

</script>
</html>
