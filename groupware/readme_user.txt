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
 - Common : �Խ��� ����Ʈ BOARD_LIST_JSON=>json

helper
 - alert_helper.php : ���â ��� page ����
 - user_permission_helper : �α��� üũ ,permission ���� �Ϸ� �ۼ� ������ ������

-------------------------------------------------------------------------------------

folder

cl/
groupware/daumeditor : ���� �ؽ�Ʈ ������
groupware/upload : ���ε� ����
groupware/lib
groupware/html/js/sw/ : �� ���������� ����� js