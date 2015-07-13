version 

APMSETUP 7 [http://www.apmsetup.com/download.php]
 - Apache 2.2.14 (openssl 0.9.8k) [ http://httpd.apache.org ]
 - PHP 5.2.12 [ http://kr.php.net / http://windows.php.net/ ]
 - Zend Optimizer v3.3.3 [ http://www.zend.com ]
 - MySQL 5.1.39 [ http://www.mysql.com ]
 - phpMyAdmin 3.2.3 [ http://www.phpmyadmin.net ]
 - CUBRID  [ http://www.cubrid.com ] , JRE  [ http://java.sun.com ]


CodeIgniter 2.2.2
-------------------------------------------------------------------------------------

CL - config,helper,hooks

autoload 
 - $autoload['libraries'] = array('session','database','pagination');
 - $autoload['helper'] = array('cookie','url','alert_helper','user_permission');

hooks
 - Common : 게시판 리스트 BOARD_LIST_JSON=>json

helper
 - alert_helper.php : 경고창 출력 page 제어
 - user_permission_helper : 로그인 체크 ,permission 관리 하려 작성 했으나 보류중

-------------------------------------------------------------------------------------

folder

cl/
groupware/daumeditor : 다음 텍스트 에디터
groupware/upload : 업로드 폴더
groupware/lib
groupware/html/js/sw/ : 각 페이지에서 사용할 js