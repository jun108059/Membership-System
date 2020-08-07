<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8"/>
    <title>Error Handling</title>
</head>
<body>

<script>
    var mention = "<?php if(!empty($alert)){echo($alert);}?>";
    if (mention !== "") {
        alert("❌" + mention + "❌");
    }

    if("<?php echo($back) ?>" === "true") {
        history.back();
    }else {
        location.href="/";
    }
</script>

</body>
</html>