<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8"/>
    <title>회원가입 페이지</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.0.min.js" ></script>
    <script type="text/javascript" src="checkSignUpForm.js"></script>
</head>
<body>
<form method="post">
    <h1>회원가입</h1>
    <input type="hidden" name="email" id="email" value="<?php echo($mail)?>">
    <h2><?php echo htmlspecialchars($mail);?> 이메일 인증이 완료되었습니다.</h2>
    <fieldset>
        <legend>입력하세요</legend>
        <table>
            <tr>
                <td>아이디<br><br></td>
                <td><input type="text" size="35" name="id" placeholder="아이디(4 ~ 30 글자)"
                           maxlength="30" required><br><br></td>
                <td><div class ="checkSignUpForm">중복 확인</div><br></td>
                <div class="memberIdComment comment"></div>
            </tr>
            <tr>
                <td>비밀번호<br><br></td>
                <td><input type="password" size="35" name="password" placeholder="비밀번호(8 ~ 20 글자)"
                           maxlength="20" required><br><br></td>
            </tr>
            <tr>
                <td>비밀번호 확인<br><br></td>
                <td><input type="password" size="35" name="password2" placeholder="비밀번호(8 ~ 20 글자)"
                           maxlength="20" class="memberPw" required><br><br></td>
                <div class="memberPw2Comment comment"></div>
            </tr>
            <tr>
                <td>이름<br><br></td>
                <td><input type="text" size="35" name="name" placeholder="이름 2 ~ 20 글자"
                           maxlength="20" class="memberPw2" required><br><br></td>
            </tr>
            <tr>
                <td>전화번호<br><br></td>
                <td><input type="text" size="35" name="phone" placeholder="010-1234-1234"
                           title="010-1234-1234 형식"
                           maxlength="15" required><br><br></td>
            </tr>
            <tr>
                <td>성별<br><br></td>
                <td>남<input type="radio" name="gender" value="M"> 여<input type="radio" name="gender" value="F" required><br><br></td>
            </tr>

        </table>

        <div class="formCheck">
            <input type="hidden" name="idCheck" class="idCheck" />
            <input type="hidden" name="pw2Check" class="pwCheck2" />
            <input type="hidden" name="eMailCheck" class="eMailCheck" />
        </div>

        <input type="submit" value="가입하기" formaction="/Membership/signUpDB"/>&nbsp;&nbsp;<input type="reset" value="다시쓰기"/>

    </fieldset>
</form>


<!---->
<!--<script>-->
<!--    $(document).ready(function(e) {-->
<!--        $(".check").on("keyup", function(){ //check라는 클래스에 입력을 감지-->
<!--            var self = $(this);-->
<!--            var id;-->
<!---->
<!--            if(self.attr("mem_user_id") === "id"){-->
<!--                id = self.val();-->
<!--            }-->
<!---->
<!--            $.post( //post방식으로 id_check.php에 입력한 userid값을 넘깁니다-->
<!--                "/Membership/checkId",-->
<!--                { id : id },-->
<!--                function(data){-->
<!--                    if(data){ //만약 data값이 전송되면-->
<!--                        self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.-->
<!--                        self.parent().parent().find("div").css("color", "#F00"); //div 태그를 찾아 css효과로 빨간색을 설정합니다-->
<!--                    }-->
<!--                }-->
<!--            );-->
<!--        });-->
<!--    });-->
<!--</script>-->
</body>
</html>