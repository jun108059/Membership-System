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
    <style>
        a { text-decoration: none; }
        .pagenum {
            display: inline-block; width: 25px;
            border: 1px solid transparent;
            color: gray; font-weight: bold;
            text-decoration: none; text-align: center;
        }
        .pagenum:hover { color: orange; border: 1px solid orange; }
        .pagenum.current { color: orange; text-decoration: underline; }
        .move_btn { color: gray; }
        .disabled { color: silver; }
        .paging_area { text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1 class="h2">&nbsp; 사용자 목록</h1><hr>
    </div>
    <div class="row">
        <table class="table table-bordered table-hover table-striped" style="table-layout: fixed">
            <thead>
            <tr>
                <th>회원번호</th>
                <th>아이디</th>
                <th>이름</th>
                <th>가입 상태</th>
                <th>상세보기</th>
                <th>탈퇴</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($userData as $row) { ?>
                <tr>
                    <td><?php echo $row['mem_idx'];?></td>
                    <td><?php echo $row['mem_user_id'];?></td>
                    <td><?php echo $row['mem_name'];?></td>
                    <td><?php if ($row['mem_status'] === 'Y') {
                            echo "정상 회원";
                        }else if($row['mem_status'] === 'N') {
                            echo "탈퇴된 회원";
                        }else {
                            echo "휴면 상태";
                        }
                        ?></td>
                    <td><a class="btn btn-primary" href="/Admin/editUser/<?php echo $row['mem_user_id'] ?>"><span class="glyphicon glyphicon-pencil"></span> Edit</a></td>
                    <td><a class="btn btn-warning" href="/Admin/deleteUser/<?php echo $row['mem_user_id'] ?>" onclick="return confirm('<?php echo $row['mem_user_id'] ?> 사용자를 삭제할까요?')">
                            <span class="glyphicon glyphicon-remove"></span>Del</a></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>

    <div class='paging_area'>
        <?php for ($p = $s_page; $p <= $e_page; $p++) { ?>
            <a href="/admin/allUserInfo/<?= $p ?>"><?= $p ?></a>

        <?php } ?>
        <div>
            <a class='move_btn' href="/admin/allUserInfo/<?= $s_page - 1 ?>">« 이전</a>
            <a class='move_btn' href="/admin/allUserInfo/<?= $e_page + 1 ?>">다음 »</a>
        </div>

    </div>


</body>
</html>