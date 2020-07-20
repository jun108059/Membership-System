 <?php
  session_start();

  $db = mysqli_connect("localhost", "root", "1234", "membership");
//  $db = new mysqli("localhost","root","1234","membership");
//  $db->set_charset("utf8");

  function mq($sql){
      global $db;
      $result = mysqli_query($db, $sql);
      if ($result === false) {
          // 보안상 에러 정보 출력하면 안 좋음
          // 실제로 서비스를 할 경우는 파일로 저장하자
          echo mysqli_error($db);
      }
      echo $sql;
      return $db->query($sql);
  }
 ?>