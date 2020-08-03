<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8"/>
    <title>회원가입 페이지</title>
</head>
<body>
<form method="post">
    <h1>회원가입</h1>
    <input type="hidden" name="email" id="email" value="<?php echo($mail) ?>">
    <input type="hidden" id="collect_id" value="n">
    <input type="hidden" id="collect_password" value="n">
    <input type="hidden" id="collect_password_reg" value="n">
    <input type="hidden" id="collect_tell" value="n">

    <h3>이메일 [<?php echo htmlspecialchars($mail); ?>] 인증이 완료되었습니다.</h3>
    <fieldset>
        <legend>입력하세요</legend>
        <table>
            <tr>
                <td>아이디<br><br></td>
                <td><input type="text" id="userId" name="userId" size="35" class="check" style="ime-mode:disabled"
                           placeholder="아이디(4 ~ 30 글자)" maxlength="30" required><br><br></td>
                <td>
                    <div>&nbsp;실시간 ID 체크</div>
                    <br></td>

            </tr>
            <tr>
                <td>비밀번호<br><br></td>
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
                    <div id="alert-success" style="display: none; color: blue; font-weight: bold;"> 😄비밀번호 일치😄✔<br><br>
                    </div>
                    <div id="alert-danger" style="display: inline; color: red; font-weight: bold;"> ❗비밀번호가 다름❗<br><br>
                    </div>
                </td>

            </tr>
            <tr>
                <td>이름<br><br></td>
                <td><input type="text" size="35" name="name" placeholder="이름 2 ~ 20 글자"
                           class="memberName" maxlength="20" required><br><br></td>
            </tr>
            <tr>
                <td>전화번호<br><br></td>
                <td><input type="text" size="35" name="phone" placeholder="010-1234-1234"
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

        </table>

        <input type="submit" id="form_sub" value="가입하기" formaction="/Membership/signUpDB"/>&nbsp;&nbsp;<input
                type="reset" value="다시쓰기"/>

    </fieldset>
</form>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.0.min.js"></script>
<script>
    $(document).ready(function () {
        // 실시간 변화 감지
        $(".check").on("keyup", function (e) { //check 라는 클래스에 입력을 감지
            // 한글 방지
            if (!(e.keyCode >= 37 && e.keyCode <= 40)) {
                var v = $(this).val();
                $(this).val(v.replace(/[^a-z0-9]/gi, ''));
            }

            $self = $(this);
            $.post( //post 방식으로 user id 값 넘기기
                "/Membership/checkId",
                {userId: $(this).val()},
                function (data) {
                    if (data === '<span class=\'status-available\'> 🟢사용 가능한 ID 입니다.🟢</span>') {
                        $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        $self.parent().parent().find("div").css("color", "#00FF99"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                        $('#collect_id').val('y');
                    } else { //만약 data 값이 전송 되면
                        $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        $self.parent().parent().find("div").css("color", "#F00"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                        $("#collect_id").val('n');
                    }
                );
        });
        // ID focus out 됐을 때 유효성 체크
        $(".check").on("blur", function () {
            let idReg = /^[a-z]+[a-z0-9]{3,29}$/g;
            if (!idReg.test($(this).val())) {
                alert("🟡아이디는 4~30자 영문자 또는 숫자이어야 합니다.🟡");
                $("#collect_id").val('n');
            } else {
                $("#collect_id").val('y');
            }
        }

        // 비밀 번호 글자 수 유효성 검사
        $(".memberPw").on("keyup", function () {
            let passwordReg = /^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/;
            if (!passwordReg.test($("#pw1").val())) {
                $("#check-pw-fail").css('display', 'inline');
                $("#check-pw-success").css('display', 'none');
                $("#collect_password_reg").val('n');
            } else {
                $("#check-pw-fail").css('display', 'none');
                $("#check-pw-success").css('display', 'inline');
                $('#collect_password_reg').val('y');
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
                    $('#collect_password').val('y');
                } else {
                    $("#alert-success").css('display', 'none');
                    $("#alert-danger").css('display', 'inline');
                    $('#collect_password').val('n');
                }
            }
        });

        // ID focus out 됐을 때 실행 & 전화 번호 중복 검사
        $(".tellCheck").on("blur", function () {
            // 중복 검사 로직
            let tellReg = /^\d{3}-\d{3,4}-\d{4}$/;
            if (!tellReg.test($(this).val())) {
                alert("❌전화번호 형식으로 입력해주세요.❌");
                $("#collect_tell_reg").val('n');
            } else {
                $("#collect_tell_reg").val('y');
            }
            $self = $(this);
            $.post( //post 방식으로 user id 값 넘기기
                "/Membership/checkPhone",
                {phone: $(this).val()},
                function (data) {
                    if (data === '<span class=\'status-available\'> 🟢사용 가능한 번호입니다.🟢</span>') {
                        $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        $self.parent().parent().find("div").css("color", "#00FF99"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                        $('#collect_tell').val('y');
                    } else { //만약 data 값이 전송 되면
                        $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        $self.parent().parent().find("div").css("color", "#F00"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                        $("#collect_tell").val('n');
                    }
                }
            );
        });

        $("#form_sub").click(function () {
            if ($('#collect_id').val() !== 'y') {
                alert('사용 중인 ID를 입력하셨습니다.');
                return false;
            } else if ($('#collect_password').val() !== 'y') {
                alert('비밀번호가 일치하지 않습니다.');
                return false;
            } else if ($('#collect_password_reg').val() !== 'y') {
                alert('비밀번호는 8~20자리로 영문, 숫자를 포함해주세요.');
                return false;
            } else if ($('#collect_tell').val() !== 'y') {
                alert('사용 중인 전화번호입니다.');
                return false;
            }
            alert("🎉회원가입이 완료되었습니다!");
            $('#form').submit();
        });
    });
</script>
</body>
</html>
