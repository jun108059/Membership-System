<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
<h1>Welcome</h1>
<p>안녕 나는 View 야! <?php echo htmlspecialchars($name); ?>!</p>
<ul>
    <?php foreach ($colours as $colour) : ?>
        <li><?php echo htmlspecialchars($colour); ?></li>
    <?php endforeach; ?>
</ul>
</body>
</html>