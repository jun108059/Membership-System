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
<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="https://github.com/jun108059/Membership-System/tree/master/membership">Membership</a>
            <ul class="nav navbar-nav">
                <li class="active"><a href="/Admin/index">메인</a></li>
                <?php if (isset($_SESSION['userID'])) { ?>
                    <li><a href="">Signed in as <?php echo $_SESSION['userID']; ?></a></li>
                    <li><a href="/Login/logout">Log Out</a></li>
                <?php } else { ?>
                    <li><a href="/home">Login</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
</body>
</html>