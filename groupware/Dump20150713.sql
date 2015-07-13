-- MySQL dump 10.13  Distrib 5.6.24, for Win64 (x86_64)
--
-- Host: localhost    Database: sw_groupware
-- ------------------------------------------------------
-- Server version	5.1.41-community

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `sw_account`
--

DROP TABLE IF EXISTS `sw_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_account` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(100) DEFAULT NULL COMMENT '아이디',
  `pwd` varchar(100) DEFAULT NULL COMMENT '비밀번호',
  `name` varchar(50) DEFAULT NULL COMMENT '이름',
  `position` varchar(20) DEFAULT NULL COMMENT '등급',
  `email` varchar(255) DEFAULT NULL COMMENT '이메일',
  `birth` datetime DEFAULT NULL COMMENT '생년월일',
  `type` int(11) DEFAULT NULL COMMENT '분류 - 지식인/블로그',
  `is_using_question` int(11) DEFAULT NULL COMMENT '사용여부 - 질문',
  `is_using_anser` int(11) DEFAULT NULL COMMENT '사용여부 - 답변',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='계정 관리\n넘겨받는 데이터 체킹요망';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_account`
--

LOCK TABLES `sw_account` WRITE;
/*!40000 ALTER TABLE `sw_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_approved`
--

DROP TABLE IF EXISTS `sw_approved`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_approved` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `project_no` int(11) NOT NULL COMMENT '업무 키 / 일반업무 등록시 ''0'' ',
  `user_no` int(11) NOT NULL COMMENT '유저 키',
  `menu_no` int(11) NOT NULL COMMENT '분류 키-> 부서 분류',
  `title` varchar(255) DEFAULT NULL COMMENT '제목',
  `sData` datetime DEFAULT NULL COMMENT '시작일',
  `eData` datetime DEFAULT NULL COMMENT '종료일',
  `file` varchar(255) DEFAULT NULL COMMENT '첨부파일',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='전자 결재';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_approved`
--

LOCK TABLES `sw_approved` WRITE;
/*!40000 ALTER TABLE `sw_approved` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_approved` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_approved_status`
--

DROP TABLE IF EXISTS `sw_approved_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_approved_status` (
  `approved_no` int(11) NOT NULL COMMENT '결제 키',
  `sender` int(11) NOT NULL COMMENT '유저 키-> 발신자',
  `receiver` int(11) NOT NULL COMMENT '유저 키-> 수신자',
  `menu_no` int(11) NOT NULL COMMENT '분류 키 -> 부서 분류',
  `status` int(11) DEFAULT NULL COMMENT '상태',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `created` datetime DEFAULT NULL COMMENT '생성일'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='전자결재 상태';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_approved_status`
--

LOCK TABLES `sw_approved_status` WRITE;
/*!40000 ALTER TABLE `sw_approved_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_approved_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_approved_status_history`
--

DROP TABLE IF EXISTS `sw_approved_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_approved_status_history` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `approved_no` int(11) NOT NULL COMMENT '결재 키',
  `serder` int(11) NOT NULL COMMENT '발신자',
  `receiver` int(11) NOT NULL COMMENT '수신자',
  `menu_no` int(11) NOT NULL COMMENT '분류 키 -> 부서 분류',
  `status` int(11) DEFAULT NULL COMMENT '상태',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='결재 경과 히스토리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_approved_status_history`
--

LOCK TABLES `sw_approved_status_history` WRITE;
/*!40000 ALTER TABLE `sw_approved_status_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_approved_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_attendance`
--

DROP TABLE IF EXISTS `sw_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_attendance` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `sData` datetime DEFAULT NULL COMMENT '출근시간',
  `eData` datetime DEFAULT NULL COMMENT '퇴근시간',
  `point` int(11) DEFAULT NULL COMMENT '점수',
  `is_active` int(11) DEFAULT NULL COMMENT '활성화',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='근태 설정\n주중/토/공휴일';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_attendance`
--

LOCK TABLES `sw_attendance` WRITE;
/*!40000 ALTER TABLE `sw_attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_attendance_history`
--

DROP TABLE IF EXISTS `sw_attendance_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_attendance_history` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `user_no` int(11) NOT NULL COMMENT '유저 키',
  `sData` datetime DEFAULT NULL COMMENT '출근시간',
  `eData` datetime DEFAULT NULL COMMENT '퇴근시간',
  `oData` time DEFAULT NULL COMMENT '지각시간',
  `point` int(11) DEFAULT NULL COMMENT '점수',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='근태 히스토리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_attendance_history`
--

LOCK TABLES `sw_attendance_history` WRITE;
/*!40000 ALTER TABLE `sw_attendance_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_attendance_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_base_code`
--

DROP TABLE IF EXISTS `sw_base_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_base_code` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) DEFAULT NULL COMMENT '키워드',
  `parent_key` varchar(50) DEFAULT NULL COMMENT '부모 키워드',
  `name` varchar(50) DEFAULT NULL COMMENT '이름',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `is_active` int(11) DEFAULT NULL COMMENT '활성화',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='기초코드';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_base_code`
--

LOCK TABLES `sw_base_code` WRITE;
/*!40000 ALTER TABLE `sw_base_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_base_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_board_comment`
--

DROP TABLE IF EXISTS `sw_board_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_board_comment` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT '키',
  `code` varchar(50) NOT NULL COMMENT '게시판 코드',
  `parent_contents_no` int(11) DEFAULT NULL COMMENT '부모 글 키',
  `parent_no` int(11) DEFAULT NULL COMMENT '부모 댓글 키',
  `depth` int(11) DEFAULT NULL COMMENT '글 깊이',
  `order` int(11) DEFAULT NULL COMMENT '글 순서',
  `user_no` int(11) DEFAULT NULL COMMENT '멤버 키',
  `user_id` varchar(50) DEFAULT NULL COMMENT '멤버 아이디',
  `user_name` varchar(50) DEFAULT NULL COMMENT '작성자',
  `contents` text COMMENT '내용',
  `is_delete` int(11) DEFAULT NULL COMMENT '삭제 여부',
  `count_comment` int(11) DEFAULT NULL COMMENT '댓글 수',
  `ip` varchar(20) DEFAULT NULL COMMENT 'IP',
  `password` varchar(20) DEFAULT NULL COMMENT '비밀번호',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='게시판 댓글';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_board_comment`
--

LOCK TABLES `sw_board_comment` WRITE;
/*!40000 ALTER TABLE `sw_board_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_board_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_board_contents`
--

DROP TABLE IF EXISTS `sw_board_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_board_contents` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT '키',
  `code` varchar(50) NOT NULL COMMENT '게시판 코드',
  `original_no` int(11) DEFAULT NULL COMMENT '최상위 원본글',
  `parent_no` int(11) DEFAULT NULL COMMENT '부모글 키',
  `depth` int(11) DEFAULT NULL COMMENT '글 깊이',
  `order` int(11) DEFAULT NULL COMMENT '글 순서',
  `user_no` int(11) DEFAULT NULL COMMENT '멤버 키',
  `user_id` varchar(50) DEFAULT NULL COMMENT '멤버 아이디',
  `user_name` varchar(50) DEFAULT NULL COMMENT '작성자',
  `subject` varchar(200) DEFAULT NULL COMMENT '제목',
  `contents` text COMMENT '내용',
  `is_notice` int(11) DEFAULT NULL COMMENT '공지',
  `is_delete` int(11) DEFAULT NULL COMMENT '삭제여부',
  `count_hit` int(11) DEFAULT NULL COMMENT '조회수',
  `count_reply` int(11) DEFAULT NULL COMMENT '답글수',
  `count_comment` int(11) DEFAULT NULL COMMENT '댓글수',
  `ip` varchar(20) DEFAULT NULL COMMENT 'IP',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='게시판';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_board_contents`
--

LOCK TABLES `sw_board_contents` WRITE;
/*!40000 ALTER TABLE `sw_board_contents` DISABLE KEYS */;
INSERT INTO `sw_board_contents` VALUES (1,'notice',1,1,0,0,1,'admin','관리자','테스트1','테스트1',1,0,3,0,0,'127.0.0.1','2015-06-10 17:42:02'),(2,'notice',1,1,1,4,1,'admin','관리자','테스트2','테스트1\r\n-----------------------------------------------------------------------------------\r\n테스트2',0,0,5,0,0,'127.0.0.1','2015-06-10 17:42:10'),(3,'notice',1,1,1,2,1,'admin','관리자','테스트3','테스트1\r\n-----------------------------------------------------------------------------------\r\n테스트3',1,0,2,0,0,'127.0.0.1','2015-06-10 17:42:16'),(4,'notice',1,1,1,1,1,'admin','관리자','테스트4','테스트1\r\n-----------------------------------------------------------------------------------\r\n테스트4',1,0,5,0,0,'127.0.0.1','2015-06-10 18:06:41'),(7,'notice',7,7,0,0,1,'admin','관리자','test','test',1,0,0,0,0,'127.0.0.1','2015-06-10 20:33:53'),(5,'notice',1,3,2,3,1,'admin','관리자','테스트5','테스트1\r\n-----------------------------------------------------------------------------------\r\n테스트3\r\n-----------------------------------------------------------------------------------\r\n테스트5',1,0,4,0,0,'127.0.0.1','2015-06-10 18:07:32'),(6,'notice',6,6,0,0,1,'admin','관리자','테스트6','테스트6',0,0,20,0,0,'127.0.0.1','2015-06-10 18:07:44'),(8,'notice',8,8,0,0,1,'admin','관리자','test','test',1,0,1,0,0,'127.0.0.1','2015-06-10 20:35:55'),(9,'test',9,9,0,0,1,'admin','관리자','test','test',1,0,3,0,0,'127.0.0.1','2015-06-10 20:36:56'),(10,'notice',10,10,0,0,1,'admin','관리자','test','내용',1,0,1,0,0,'127.0.0.1','2015-06-22 20:01:19'),(11,'test',11,11,0,0,1,'admin','관리자','test','test',1,0,2,0,0,'127.0.0.1','2015-07-10 12:47:50');
/*!40000 ALTER TABLE `sw_board_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_board_file`
--

DROP TABLE IF EXISTS `sw_board_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_board_file` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT '키',
  `code` varchar(50) NOT NULL COMMENT '게시판 코드',
  `parent_no` int(11) NOT NULL COMMENT '부모글 키',
  `original_name` varchar(200) NOT NULL COMMENT '파일명',
  `upload_name` varchar(200) NOT NULL COMMENT '실제 파일명',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_board_file`
--

LOCK TABLES `sw_board_file` WRITE;
/*!40000 ALTER TABLE `sw_board_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_board_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_board_list`
--

DROP TABLE IF EXISTS `sw_board_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_board_list` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT '키',
  `code` varchar(50) NOT NULL COMMENT '게시판 코드',
  `type` varchar(50) NOT NULL DEFAULT 'default' COMMENT '게시판 종류',
  `name` varchar(100) NOT NULL COMMENT '게시판 이름',
  `activated` int(11) NOT NULL DEFAULT '1' COMMENT '활성화 여부',
  `permission` varchar(50) DEFAULT NULL COMMENT '권한 - 추후수정',
  `reply` int(11) NOT NULL DEFAULT '1' COMMENT '답변사용 여부',
  `comment` int(11) NOT NULL DEFAULT '1' COMMENT '댓글사용 여부',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '순서',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_board_list`
--

LOCK TABLES `sw_board_list` WRITE;
/*!40000 ALTER TABLE `sw_board_list` DISABLE KEYS */;
INSERT INTO `sw_board_list` VALUES (1,'notice','default','공지사항',0,NULL,1,1,10),(2,'petition','default','건의사항',0,NULL,1,1,20),(3,'free','default','자유게시판',0,NULL,0,0,30),(4,'files','default','자료실',0,NULL,1,1,40),(5,'test','default','테스트',0,NULL,0,0,0),(6,'adasdasdasd','default','TEST',0,NULL,1,1,0);
/*!40000 ALTER TABLE `sw_board_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_document`
--

DROP TABLE IF EXISTS `sw_document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_document` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `menu_no` int(11) NOT NULL COMMENT '분류 키 -> 서식 분류',
  `user_no` int(11) NOT NULL COMMENT '유저 키 - 작성자',
  `name` varchar(255) DEFAULT NULL COMMENT '제목',
  `contents` text COMMENT '내용',
  `file` varchar(255) DEFAULT NULL COMMENT '첨부파일',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `is_active` int(11) DEFAULT NULL COMMENT '활성화 [ 사용/ 미사용 ]',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='서식관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_document`
--

LOCK TABLES `sw_document` WRITE;
/*!40000 ALTER TABLE `sw_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_holiday`
--

DROP TABLE IF EXISTS `sw_holiday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_holiday` (
  `name` varchar(100) DEFAULT NULL COMMENT '이름',
  `data` datetime DEFAULT NULL COMMENT '일자',
  `order` int(11) DEFAULT NULL COMMENT '정렬'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='휴일설정';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_holiday`
--

LOCK TABLES `sw_holiday` WRITE;
/*!40000 ALTER TABLE `sw_holiday` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_holiday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_information`
--

DROP TABLE IF EXISTS `sw_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_information` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(20) NOT NULL COMMENT '페이지명 또는 분류',
  `bizName` varchar(100) DEFAULT NULL COMMENT '상호명',
  `ceoName` varchar(100) DEFAULT NULL COMMENT '대표자명',
  `gubun` varchar(255) DEFAULT NULL COMMENT '구분-뭔지 모름',
  `bizType` varchar(100) DEFAULT NULL COMMENT '업종',
  `bizCondition` varchar(100) DEFAULT NULL COMMENT '업태',
  `addr` varchar(255) DEFAULT NULL COMMENT '주소',
  `phone` varchar(20) DEFAULT NULL COMMENT '전화',
  `fax` varchar(20) DEFAULT NULL COMMENT '팩스',
  `bigo` text COMMENT '비고',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='회사정보,거래처,develop,marketing 등 정보';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_information`
--

LOCK TABLES `sw_information` WRITE;
/*!40000 ALTER TABLE `sw_information` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_information_site`
--

DROP TABLE IF EXISTS `sw_information_site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_information_site` (
  `information_no` int(11) NOT NULL COMMENT '정보 키',
  `url` varchar(200) DEFAULT NULL COMMENT 'url',
  `id` varchar(100) DEFAULT NULL COMMENT '아이디',
  `pwd` varchar(100) DEFAULT NULL COMMENT '비밀번호',
  `bigo` varchar(255) DEFAULT NULL COMMENT '비고'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='정보 - 사이트';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_information_site`
--

LOCK TABLES `sw_information_site` WRITE;
/*!40000 ALTER TABLE `sw_information_site` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_information_site` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_information_staff`
--

DROP TABLE IF EXISTS `sw_information_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_information_staff` (
  `information_no` int(11) NOT NULL COMMENT '정보 키',
  `name` varchar(50) DEFAULT NULL COMMENT '이름',
  `part` varchar(50) DEFAULT NULL COMMENT '부서',
  `position` varchar(50) DEFAULT NULL COMMENT '직급',
  `phone` varchar(20) DEFAULT NULL COMMENT '전화',
  `ext` varchar(10) DEFAULT NULL COMMENT '내선번호',
  `email` varchar(255) DEFAULT NULL COMMENT '이메일',
  `order` int(11) DEFAULT NULL COMMENT '정렬'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='정보 - 담당자';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_information_staff`
--

LOCK TABLES `sw_information_staff` WRITE;
/*!40000 ALTER TABLE `sw_information_staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_information_staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_meeting`
--

DROP TABLE IF EXISTS `sw_meeting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_meeting` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `menu_no` int(11) NOT NULL COMMENT '메뉴 키 -> 서식 분류',
  `user_no` int(11) NOT NULL COMMENT '유저 키 -> 작성자',
  `name` varchar(255) DEFAULT NULL COMMENT '제목',
  `contents` text COMMENT '내용',
  `file` varchar(255) DEFAULT NULL COMMENT '첨부파일',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `is_active` int(11) DEFAULT NULL COMMENT '활성화 [ 사용/ 미사용 ] ',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='회의관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_meeting`
--

LOCK TABLES `sw_meeting` WRITE;
/*!40000 ALTER TABLE `sw_meeting` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_meeting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_menu`
--

DROP TABLE IF EXISTS `sw_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_menu` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `parent_no` int(11) NOT NULL DEFAULT '0' COMMENT '부모 키',
  `category` varchar(20) NOT NULL COMMENT '페이지 명 또는 분류',
  `name` varchar(100) DEFAULT NULL COMMENT '이름',
  `color` varchar(6) DEFAULT NULL COMMENT '색상',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `is_active` int(11) DEFAULT NULL COMMENT '활성화',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='트리메뉴 \n부서,물품,규정,서식,회의,업무 등';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_menu`
--

LOCK TABLES `sw_menu` WRITE;
/*!40000 ALTER TABLE `sw_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_object`
--

DROP TABLE IF EXISTS `sw_object`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_object` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `menu_no` int(11) NOT NULL COMMENT '분류 키 -> 물품분류',
  `user_no` int(11) NOT NULL COMMENT '유저 키 참조 - 관리자',
  `name` varchar(255) DEFAULT NULL COMMENT '물품명',
  `area` varchar(255) DEFAULT NULL COMMENT '물품 위치',
  `bigo` text COMMENT '비고',
  `file` varchar(255) DEFAULT NULL COMMENT '첨부 파일',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `is_active` int(11) DEFAULT NULL COMMENT '활성화 [사용/미사용]',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='물품관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_object`
--

LOCK TABLES `sw_object` WRITE;
/*!40000 ALTER TABLE `sw_object` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_object` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_other_point`
--

DROP TABLE IF EXISTS `sw_other_point`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_other_point` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `menu_no` int(11) NOT NULL COMMENT '분류 키 -> 해당 유저의 부서 분류',
  `user_no` int(11) NOT NULL COMMENT '유저 키',
  `title` varchar(255) DEFAULT NULL COMMENT '제목',
  `operator` varchar(5) DEFAULT NULL COMMENT '연산자',
  `point` int(11) DEFAULT NULL COMMENT '점수',
  `sPoint` int(11) DEFAULT NULL COMMENT '누적점수 - 필요할지 모르겠음 ',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='추가평점';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_other_point`
--

LOCK TABLES `sw_other_point` WRITE;
/*!40000 ALTER TABLE `sw_other_point` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_other_point` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_project`
--

DROP TABLE IF EXISTS `sw_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_project` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `menu_part_no` int(11) NOT NULL COMMENT '분류 키 -> 부서 분류',
  `menu_no` int(11) NOT NULL COMMENT '분류 키 -> 업무 분류',
  `user_no` int(11) NOT NULL COMMENT '유저 키 -> 작성자',
  `title` varchar(255) DEFAULT NULL COMMENT '제목',
  `sData` datetime DEFAULT NULL COMMENT '시작일',
  `eData` datetime DEFAULT NULL COMMENT '종료일',
  `pPoint` int(11) DEFAULT NULL COMMENT '결재 점수',
  `mPoint` int(11) DEFAULT NULL COMMENT '누락 점수',
  `file` varchar(255) DEFAULT NULL COMMENT '첨부파일',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='업무관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_project`
--

LOCK TABLES `sw_project` WRITE;
/*!40000 ALTER TABLE `sw_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_project_staff`
--

DROP TABLE IF EXISTS `sw_project_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_project_staff` (
  `project_no` int(11) NOT NULL COMMENT '업무 키',
  `menu_no` int(11) DEFAULT NULL COMMENT '분류 키 -> 부서 분류',
  `user_no` int(11) DEFAULT NULL COMMENT '유저 키',
  `order` int(11) DEFAULT NULL COMMENT '정렬'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='업무 담당자';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_project_staff`
--

LOCK TABLES `sw_project_staff` WRITE;
/*!40000 ALTER TABLE `sw_project_staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_project_staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_rule`
--

DROP TABLE IF EXISTS `sw_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_rule` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `menu_no` int(11) NOT NULL COMMENT '분류 키 -> 규정분류',
  `user_no` int(11) NOT NULL COMMENT '유저 키 -> 작성자',
  `name` varchar(255) DEFAULT NULL COMMENT '제목',
  `contents` text COMMENT '내용',
  `operator` varchar(5) DEFAULT NULL COMMENT '연산자',
  `point` int(11) DEFAULT NULL COMMENT '점수',
  `file` varchar(255) DEFAULT NULL COMMENT '첨부파일',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `is_active` int(11) DEFAULT NULL COMMENT '활성화 [사용/미사용]',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='규정관리';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_rule`
--

LOCK TABLES `sw_rule` WRITE;
/*!40000 ALTER TABLE `sw_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_user`
--

DROP TABLE IF EXISTS `sw_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_user` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(50) NOT NULL COMMENT '아이디',
  `pwd` varchar(255) NOT NULL COMMENT '비밀번호',
  `name` varchar(50) DEFAULT NULL COMMENT '이름',
  `level` int(11) DEFAULT NULL COMMENT '레벨',
  `phone` varchar(20) DEFAULT NULL COMMENT '전화',
  `mobile` varchar(20) DEFAULT NULL COMMENT '휴대폰',
  `email` varchar(255) DEFAULT NULL COMMENT '이메일',
  `addr` varchar(255) DEFAULT NULL COMMENT '주소',
  `annual` int(11) DEFAULT NULL COMMENT '연차갯수',
  `sData` datetime DEFAULT NULL COMMENT '연차 적용 시작일',
  `eData` datetime DEFAULT NULL COMMENT '연차 적용 종료일',
  `birth` datetime DEFAULT NULL COMMENT '생년월일',
  `gender` int(11) DEFAULT NULL COMMENT '성별',
  `inData` datetime DEFAULT NULL COMMENT '입사일',
  `outData` datetime DEFAULT NULL COMMENT '퇴사일',
  `color` varchar(6) DEFAULT NULL COMMENT '색상',
  `file` varchar(255) DEFAULT NULL COMMENT '사진',
  `order` int(11) DEFAULT NULL COMMENT '정렬',
  `is_active` int(11) DEFAULT NULL COMMENT '활성화 [재직/퇴사]',
  `created` datetime DEFAULT NULL COMMENT '생성일',
  PRIMARY KEY (`no`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='유저 테이블';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_user`
--

LOCK TABLES `sw_user` WRITE;
/*!40000 ALTER TABLE `sw_user` DISABLE KEYS */;
INSERT INTO `sw_user` VALUES (1,'admin','admin','관리자',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2015-07-13 09:54:24');
/*!40000 ALTER TABLE `sw_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_user_annual`
--

DROP TABLE IF EXISTS `sw_user_annual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_user_annual` (
  `user_no` int(11) NOT NULL COMMENT '유저 키 참조',
  `name` varchar(50) DEFAULT NULL COMMENT '제목',
  `data` datetime DEFAULT NULL COMMENT '사용일',
  `order` int(11) DEFAULT NULL COMMENT '정렬'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='유저 연차 사용일';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_user_annual`
--

LOCK TABLES `sw_user_annual` WRITE;
/*!40000 ALTER TABLE `sw_user_annual` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_user_annual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_user_department`
--

DROP TABLE IF EXISTS `sw_user_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_user_department` (
  `user_no` int(11) NOT NULL COMMENT '유저 키 참조',
  `menu_no` int(11) NOT NULL COMMENT '분류[ 부서 ] 참조',
  `position` varchar(50) DEFAULT NULL COMMENT '직급',
  `bigo` varchar(255) DEFAULT NULL COMMENT '비고',
  `order` int(11) DEFAULT NULL COMMENT '정렬'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='유저 부서/직급';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_user_department`
--

LOCK TABLES `sw_user_department` WRITE;
/*!40000 ALTER TABLE `sw_user_department` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_user_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sw_user_permission`
--

DROP TABLE IF EXISTS `sw_user_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sw_user_permission` (
  `user_no` int(11) NOT NULL COMMENT '유저 키 참조',
  `category` varchar(20) DEFAULT NULL COMMENT '페이지 명 또는 분류',
  `name` varchar(100) DEFAULT NULL COMMENT '이름',
  `permission` varchar(255) DEFAULT NULL COMMENT '권한'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='유저 권한';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sw_user_permission`
--

LOCK TABLES `sw_user_permission` WRITE;
/*!40000 ALTER TABLE `sw_user_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `sw_user_permission` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-13 10:55:28
