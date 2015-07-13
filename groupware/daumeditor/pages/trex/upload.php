<?php

$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/upload/board/';
$urldir    = 'http://' . $_SERVER['SERVER_NAME'].'/upload/board/';

$file_name = $_FILES['upload_file']['name'];
$tmp_file  = $_FILES['upload_file']['tmp_name'];

$file_path = $uploaddir . $file_name;
$image_url = $urldir . $file_name;

$r = move_uploaded_file($tmp_file, $file_path);

$file_size = $_FILES["upload_file"]["size"];


echo $file_name . "<br>";
echo $tmp_file . "<br>";
echo $file_path . "<br>";
echo $image_url . "<br>";
echo $file_size . "<br>";
echo $r . "<br>";


$UpFile = $HTTP_POST_FILES["ImageFile"][name];
if($UpFile){
	$FileName = GetUniqFileName($UpFile, $SavePath); // 같은 화일 이름이 있는지 검사
	move_uploaded_file($HTTP_POST_FILES["ImageFile"][tmp_name],"$SavePath$FileName"); // 화일을 업로드 위치에 저장
}







function GetUniqFileName($FN, $PN){
	$FileExt = substr(strrchr($FN, "."), 1); // 확장자 추출
	$FileName = substr($FN, 0, strlen($FN) - strlen($FileExt) - 1); // 화일명 추출

	$ret = "$FileName.$FileExt";
	while(file_exists($PN.$ret)){
		$FileCnt++;
		$ret = $FileName."_".$FileCnt.".".$FileExt; // 화일명뒤에 (_1 ~ n)의 값을 붙여서....
	}

	return($ret); // 중복되지 않는 화일명 리턴
}
?> 