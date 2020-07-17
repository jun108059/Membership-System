-- --------------------------------------------------------
-- 호스트:                          127.0.0.1
-- 서버 버전:                        8.0.21 - MySQL Community Server - GPL
-- 서버 OS:                        Win64
-- HeidiSQL 버전:                  11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE IF NOT EXISTS `membership` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `membership`;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user`
(
    `mem_idx`      int unsigned                                                        NOT NULL AUTO_INCREMENT COMMENT '회원번호(P.K)',
    `mem_user_id`  varchar(30)                                                         NOT NULL COMMENT '로그인 ID',
    `mem_email`    varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci        NOT NULL COMMENT 'E-mail',
    `mem_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                 DEFAULT NULL COMMENT '비밀번호',
    `mem_status`   enum ('Y','N','H')                                                  NOT NULL DEFAULT 'Y' COMMENT 'Y:정상, N:탈퇴, H:휴면',
    `mem_cert`     enum ('Y','N')                                                      NOT NULL DEFAULT 'N' COMMENT '본인 인증 여부',
    `mem_name`     varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci                 DEFAULT NULL COMMENT '이름',
    `mem_phone`    varchar(20)                                                                  DEFAULT NULL COMMENT '핸드폰번호',
    `mem_gender`   enum ('M','F','U') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'U' COMMENT 'M:남성, F:여성 U:모름',
    `mem_level`    smallint unsigned                                                   NOT NULL DEFAULT '4' COMMENT '권한 level(1~4) (super1, 사용자4)',
    `mem_reg_dt`   datetime                                                            NOT NULL COMMENT '가입 일시',
    `mem_log_dt`   datetime                                                            NOT NULL COMMENT '마지막 로그인 일시',
    `mem_pw_dt`    datetime                                                            NOT NULL COMMENT '마지막 비밀번호 변경일시',
    PRIMARY KEY (`mem_idx`) USING BTREE,
    UNIQUE KEY `mem_email` (`mem_email`),
    UNIQUE KEY `mem_user_id` (`mem_user_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 5
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT ='사용자 계정 Table';

--
-- Dumping data for table `member`
--

INSERT INTO `user`
    (`mem_user_id`, `mem_email`, `mem_password`, `mem_status`, `mem_cert`, `mem_name`,
    `mem_phone`, `mem_gender`, `mem_level`, `mem_reg_dt`, `mem_log_dt`, `mem_pw_dt`)
    VALUES ('admin', 'admin@admin.com', '$2y$10$crvWQLmA3Yew2wAaxtJqAeqC.3tpdpMNxXAbmmcAmqQAjbAiJv3ty', 'Y', 'Y', '관리자',
            '010-1234-1234', 'M', 1, date('Y-m-d H:i:s', '1299762201428'), date('Y-m-d H:i:s', '1299762201428'),
            date('Y-m-d H:i:s', '1299762201428'));



-- 테이블 데이터 membership.user:~0 rows (대략적) 내보내기
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;