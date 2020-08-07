<?php

static $db = null;

if ($db === null) {
    $host = 'localhost';
    $dbname = 'membership';
    $username = 'root';
    $password = '1234';

    try {
        // DB 연결 코드
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",
            $username, $password);

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// 쿼리를 담은 PDOStatement 객체 생성 - return PDOStatement 객체
$stmt = $db->prepare("SELECT mem_idx FROM user");
// PDOStatement 객체가 가진 쿼리를 실행
$stmt->execute();
// 총 User 수 반환
$num = $stmt->rowCount();

$page = ($_GET['page'])?$_GET['page']:1;
$list = 10;
$block = 3;

$pageNum = ceil($num/$list); // 총 페이지
$blockNum = ceil($pageNum/$block); // 총 블록
$nowBlock = ceil($page/$block);

$s_page = ($nowBlock * $block) - 2;
if ($s_page <= 1) {
    $s_page = 1;
}
$e_page = $nowBlock*$block;
if ($pageNum <= $e_page) {
    $e_page = $pageNum;
}


echo "현재 페이지는".$page."<br/>";
echo "현재 블록은".$nowBlock."<br/>";

echo "현재 블록의 시작 페이지는".$s_page."<br/>";
echo "현재 블록의 끝 페이지는".$e_page."<br/>";

echo "총 페이지는".$pageNum."<br/>";
echo "총 블록은".$blockNum."<br/>";

for ($p=$s_page; $p<=$e_page; $p++) {
    ?>

    <a href="/admin/<?=$p?>/test/"><?=$p?></a>

    <?php
}
?>
<div>
    <a href="/admin/<?=$s_page-1?>/test/">이전</a>
    <a href="/admin/<?=$e_page+1?>/test/">다음</a>
<!--    <a href="--><?//=$PHP_SELP?><!--?page=--><?//=$e_page+1?><!--">다음</a>-->
</div>


<?php
$s_point = ($page-1) * $list;

// 쿼리를 담은 PDOStatement 객체 생성 - return PDOStatement 객체
$stmt = $db->prepare("SELECT * FROM user ORDER BY mem_idx LIMIT $s_point,$list");

// PDOStatement 객체가 가진 쿼리를 실행
$stmt->execute();

$userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($userData as $row) { ?>
    <div>
        <?= $row['mem_idx'] ?>
    </div>
<?php
}
?>

