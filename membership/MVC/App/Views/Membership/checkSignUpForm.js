$(function(){
    //아이디 중복 확인 소스
    var checkSignUpForm = $('.checkSignUpForm');
    var memberId = $('.id');
    var memberIdComment = $('.memberIdComment');
    var memberPw = $('.password');
    var memberPw2 = $('.password2');
    var memberPw2Comment = $('.memberPw2Comment');
    var idCheck = $('.idCheck');
    var pwCheck2 = $('.pwCheck2');

    checkSignUpForm.click(function(){
        console.log(memberId.val());
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/Membership/checkId',
            data: {memberId: memberId.val()},

            success: function (json) {
                if(json.res === 'good') {
                    console.log(json.res);
                    memberIdComment.text('사용가능한 아이디 입니다.');
                    idCheck.val('1');
                }else{
                    memberIdComment.text('다른 아이디를 입력해 주세요.');
                    memberId.focus();
                }
            },

            error: function(){
                console.log('failed');

            }
        })
    });

    //비밀번호 동일 한지 체크
    memberPw2.blur(function(){
        if(memberPw.val() === memberPw2.val()){
            memberPw2Comment.text('비밀번호가 일치합니다.');
            pwCheck2.val('1');
        }else{
            memberPw2Comment.text('비밀번호가 일치하지 않습니다.');

        }
    });

});