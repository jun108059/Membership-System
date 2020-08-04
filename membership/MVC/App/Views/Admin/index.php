<?php
include('head.php');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta http-equiv="Content-Type" content="text/html;
    charset=UTF-8" />
    <title>멤버쉽 시스템</title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <script src="/bootstrap/js/bootstrap.min.js"></script>
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
                    <td><a class="btn btn-primary" href="editform.php?edit_id=<?php echo $username ?>"><span class="glyphicon glyphicon-pencil"></span> Edit</a></td>
                    <td><a class="btn btn-warning" href="delete.php?del_id=<?php echo $username ?>" onclick="return confirm('<?php echo $username ?> 사용자를 삭제할까요?')">
                            <span class="glyphicon glyphicon-remove"></span>Del</a></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    </body>
    </html>