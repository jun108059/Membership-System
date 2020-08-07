<?php
include('head.php');
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta http-equiv="Content-Type" content="text/html;
    charset=UTF-8" />
    <title>사용자 정보</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <script src="/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div>
        <h1 class="h2" align="center">&nbsp; 사용자 정보 수정<a class="btn btn-success" href="/Admin/index" style="margin-left: 850px"><span class="glyphicon glyphicon-home"></span>&nbsp; Back</a></h1><hr>
    </div>
    <form id="myForm" class="form-horizontal" method="post" style="margin: 0 150px 0 150px;border: solid 1px;border-radius:4px">

        <input type="hidden" id="collect_password" value="n">
        <input type="hidden" id="collect_password_reg" value="n">
        <input type="hidden" id="collect_tell" value="n">
        <input type="hidden" id="collect_name" value="n">

        <table width="500" height="650" class="table table-responsive">
            <tr>
                <td><label class="control-label" style="color: dodgerblue;">아이디</label></td>
                <td>
                    <input type="text" id="userId" name="userId" class="form-control" value="<?php echo $user_id; ?>"
                           autocomplete="off" readonly />
                </td>
                <td>
                    <label class="control-label" style="color: dodgerblue;"> 수정 불가능</label>
                </td>
            </tr>
            <tr>
                <td><label class="control-label" style="color: dodgerblue;">이메일</label></td>
                <td>
                    <input type="text" id="userEmail" name="userEmail" class="form-control" value="<?php echo $user_email; ?>"
                           autocomplete="off" readonly />
                </td>
                <td>
                    <label class="control-label" style="color: dodgerblue;"> 수정 불가능</label>
                </td>
            </tr>
            <tr>
                <td><label class="control-label">비밀번호</label></td>
                <td><input type="password" name="password" placeholder="비밀번호(8 ~ 20 글자)" autocomplete="off"
                           class="form-control" id="pw1" maxlength="20"
                           readonly onfocus="this.removeAttribute('readonly');" required></td>
                <td>
                    <div id="check-pw-success" style="display: none; color: limegreen; font-weight: bold;">&nbsp;🟢사용가능한 비밀번호🟢</div>
                    <div id="check-pw-fail" style="display: inline; color: orange; font-weight: bold;">&nbsp;❌8~20자리로 영문, 숫자 포함❌</div>
                </td>
            </tr>
            <tr>
                <td><label class="control-label">비밀번호 확인</label></td>
                <td><input type="password" name="password2" placeholder="비밀번호 확인" autocomplete="off"
                           class="form-control" id="pw2" maxlength="20"
                           readonly onfocus="this.removeAttribute('readonly');" required></td>
                <td>
                    <div id="alert-success" style="display: none; color: blue; font-weight: bold;"> 😄비밀번호 일치😄✔
                    </div>
                    <div id="alert-danger" style="display: inline; color: red; font-weight: bold;"> ❗비밀번호가 다름❗
                    </div>
                </td>

            </tr>
            <tr>
                <td><label class="control-label">이름</label></td>
                <td><input type="text" name="name" value="<?php echo $user_name; ?>" placeholder="이름 2 ~ 20 글자" autocomplete="off"
                           class="form-control" readonly onfocus="this.removeAttribute('readonly');" required></td>
                <td>
                    <label class="control-label" id="name-available" style="display: none; color: blue; font-weight: bold;"> ✔사용 가능한 이름입니다.</label>
                    <label class="control-label" id="name-disable" style="display: inline; color: red; font-weight: bold;"> ❌이름은 한글 또는 영문 (2~20)</label>
                </td>
            </tr>
            <tr>
                <td><label class="control-label">전화번호</label></td>
                <td><input type="text" name="phone" value="<?php echo $user_phone; ?>" placeholder="010-1234-1234" autocomplete="off"
                           id="tell" class="form-control" title="010-1234-1234 형식" maxlength="13"
                           readonly onfocus="this.removeAttribute('readonly');" required></td>
                <td>
                    <div>&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td><label class="control-label">User 권한</label></td>
                <td><input type="text" name="level" value="<?php echo $user_level ?>" placeholder="관리자 = 1, 사용자 = 4" autocomplete="off"
                           class="form-control" readonly onfocus="this.removeAttribute('readonly');" required></td>
                <td>
                    <label class="control-label"> </label>
                </td>
            </tr>
            <tr>
                <td><label class="control-label" style="color: dodgerblue;">성별</label></td>
                <td>
                    <input type="text" id="userGender" name="userGender" class="form-control" value="<?php echo $user_gender; ?>"
                           autocomplete="off" readonly />
                </td>
                <td>
                    <label class="control-label" style="color: dodgerblue;"> 수정 불가능</label>
                </td>
            </tr>
            <tr>
                <td><label class="control-label" style="color: dodgerblue;">가입일</label></td>
                <td>
                    <input type="text" id="userReg" name="userReg" class="form-control" value="<?php echo $user_reg_dt; ?>"
                           autocomplete="off" readonly />
                </td>
                <td>
                    <label class="control-label" style="color: dodgerblue;"> 수정 불가능</label>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <button type="submit" class="btn btn-primary" formaction="/Admin/userInfoUpdate"><span class="glyphicon glyphicon-floppy-save"></span>&nbsp; 업데이트</button>
                </td>
                <td colspan="2" align="center">
                    <a class="btn btn-warning" href="/Admin/index"> <span class="glyphicon glyphicon-remove"></span>&nbsp; 취소</a>
                </td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.0.min.js"></script>
<script>
    $(document).ready(function () {

        // 비밀 번호 글자 수 유효성 검사
        $("#pw1").on("keyup", function () {
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
        $("#pw2").on("keyup", function () {
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
        $("#tell").on("blur", function () {
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
                        //if(($(this).val()) === (<?php //echo $user_phone; ?>//)) {
                        //    data = '<span class=\'status-available\'> 🟡현재 번호를 유지합니다.🟡</span>'
                        //    $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        //    $self.parent().parent().find("div").css("color", "#FF0"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                        //    $('#collect_tell').val('y');
                        //}else {
                        $self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                        $self.parent().parent().find("div").css("color", "#F00"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                        $("#collect_tell").val('n');
                        // }
                    }
                }
            );
        });

        $("#form_sub").click(function () {
            if ($('#collect_password').val() !== 'y') {
                alert('비밀번호가 일치하지 않습니다.');
                return false;
            } else if ($('#collect_password_reg').val() !== 'y') {
                alert('비밀번호는 8~20자리로 영문, 숫자를 포함해주세요.');
                return false;
            } else if ($('#collect_tell').val() !== 'y') {
                alert('사용 중인 전화번호입니다.');
                return false;
            }
            alert("🎉회원정보 수정이 완료되었습니다!");
            $('#form').submit();
        });
    });
</script>
</body>
</html>


