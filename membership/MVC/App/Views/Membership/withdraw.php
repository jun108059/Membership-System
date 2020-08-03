<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8"/>
    <title>회원 탈퇴</title>
    <style>
        * {margin: 0 auto;}
        a {color:#333; text-decoration: none;}
        .change {text-align:center; width:900px; margin-top:30px; }
    </style>
</head>
<body>
<div class="change">
    <form id="myForm" action="/Login/index" method="post" >
        <h1>❗회원 탈퇴❗</h1>
        <br>
        <input type="hidden" name="user_id" id="user_id" value="<?php echo($user_id)?>">
        <input type="hidden" name="user_pw" id="user_pw" value="<?php echo($user_pw)?>">
        <input type="hidden" id="collect_password" value="n">

        <h3>[<?php echo htmlspecialchars($user_id);?>] 님 회원탈퇴 페이지 입니다.</h3>
        <br>
        <fieldset>
            <legend>현재 비밀번호와 탈퇴 사유를 입력해주세요.</legend>
            <table>
                <tr>
                    <td style="color: mediumblue; font-weight: bold;">현재 비밀번호<br><br></td>
                    <td><input type="password" size="35" name="password" placeholder="사용중인 비밀번호"
                               class="checkPw" id="curr_pw" maxlength="20" required><br><br></td>
                    <td><div>&nbsp;현재 비밀번호를 입력하세요.</div><br></td>

                <tr>
                <tr>
                    <td>탈퇴 사유<br><br></td>
                    <td><textarea cols="40" rows="8" name="reason" placeholder="탈퇴 사유를 간단히 작성해주세요.(30자 내)"
                               class="wd_reason" id="reason" maxlength="30" required ></textarea><br><br></td>
                </tr>
            </table>
            <input type="button" id="withdraw" value="탈퇴하기" />
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
                    if(data === '<span class=\'status-available\'> 🟢일치합니다.🟢</span>') {
                        $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        $self.parent().parent().find("div").css("color", "#00FF99"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                        $('#collect_password').val('y');
                    }
                    else{ //만약 data 값이 전송 되면
                        $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        $self.parent().parent().find("div").css("color", "#F00"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                        $('#collect_password').val('n');
                    }
                }
            );
        });
        $('#withdraw').click(function () {
            var result = confirm('탈퇴하시면 정보를 다시 복구할 수 없습니다.');

            var userID = $('#user_id').val();
            var reason = $('#reason').val();

            if (reason === '') {
                alert('탈퇴 사유를 입력해주세요');
                return false;
            }else if (result) {
                //yes
                if ($('#collect_password').val() !== 'y') {
                    alert('비밀번호가 일치하지 않습니다.');
                    return false;
                }else {
                    $.ajax({
                        url: "/Membership/withDraw",
                        method: 'POST',
                        data: {userID: userID, reason: reason},
                        dataType: "json",
                        async: false
                    }).done(function (data) {
                        if (data.result === 'success') {
                            alert('회원 탈퇴가 완료되었습니다.');
                            $('#myForm').submit();
                        } else if (data.alert !== ''){
                            alert(data.alert);
                        }else{
                            alert("오류가 발생했습니다.");
                        }
                        return false;
                    });
                    // location.replace('/Membership/withDraw');
                }
            } else {
                //no - nothing
            }
        });
    });
</script>

</body>
</html>