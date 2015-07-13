<?php
/**
* @file FileDir.php
*
* @class FileDir
*
* @bref 파일과 디렉토리 함수
*
* @date 2009
*
* @author 너구리안주(impactlife@naver.com)
*
* @license 이 주석(클래스 명세주석)을 지우지 않는 한 Free
*
* @section MODIFYINFO
* 	- 없음/없음
*
* @section Example
*   - 없음
*/

class FileDir{

	/**
	* @bref
	*  - 파일 이름과 확장자를 배열로 나눠서 리턴
	*  - 내장함수 pathinfo 를 사용했으나 한글이 제대로 안된다.
	**/
	public static function filename_parse($file_name){
		$arr = array();

		$pos = strrpos($file_name, '/');
		if($pos > -1)$file_name = substr($file_name, $pos+1);
		$pos = strrpos($file_name, '.');

		$name = substr($file_name, 0, $pos);
		$extension = substr($file_name, $pos+1);
		$arr[0] = $name;
		$arr[1] = $extension;
		$arr['name'] = $name;
		$arr['extension'] = $extension;

		return $arr;
	}

	/**
	* @bref
	*   - 확장자 알아내기
	**/
	public static function getExtName($str){
		return substr($str, strrpos($str,'.')+1, strlen($str));
	}

	/**
	* @bref
	*   - 지정한 풀경로 탐색하며 만들기
	**/
	public static function autoMkDir($dir, $perm=0707){
		//디렉토리생성
		$arr = explode('/', $dir);
		$arrdir = array();
		for($i=0;$i<count($arr); $i++){
			if($arr[$i] == '.' || $arr[$i] == '..' || trim($arr[$i]) == ''){
				$arrdir[$i] = $arr[$i];
				continue;
			}else{
				$parent = @implode('/', $arrdir);
				$arrdir[$i] = $arr[$i];
				$path = @implode('/', $arrdir);
				if(!is_dir($path)){
					
					if(!@mkdir($path, $perm, true))
						throw new Exception('디렉토리를 만들 수 없습니다\\n\\n상위폴더의 퍼미션을 707로 변경해 주세요.\\n\\n디렉토리경로 : '.$path);
						//throw new Exception(Lang::get()->filedir_cannot_createfile_perm.realpath($parent));
					
					if(!@chmod($path, $perm))
						throw new Exception('디렉토리에 쓰기 권한을 부여할 수 없습니다\\n\\n상위폴더의 퍼미션을 707로 변경해 주세요.\\n\\n디렉토리경로 : '.$path);
						//throw new Exception(Lang::get()->filedir_connot_changeperm.realpath($path));
				}
//				exec('chmod -R 0707 '.$temp);
			}
		}
		return implode('/', $arrdir);
	}
	
	/**
	* @bref 디렉토리와 그안의 내용들 다 지움
	* 	- 수정 : 2014.02.13 , 젤 처음에 is_dir 추가
	* @param string $dir
	**/
	public static function rmdirAll($dir){
		if(is_dir($dir)){
			$dirs = dir($dir);
			while(false !== ($entry = $dirs->read())) {
				if(($entry != '.') && ($entry != '..')) {
					if(is_dir($dir.'/'.$entry)) {
						rmdirAll($dir.'/'.$entry);
					} else {
						@unlink($dir.'/'.$entry);
					}
				}
			}
			$dirs->close();
			@rmdir($dir);
		}
	}

	/**
	* @bref
	*   - 파일 내용 읽기
	**/
	public static function readFileContent($filepath, $mode='r'){
		$f = fopen($filepath, $mode);
		$str = '';
		if(is_resource($f)){
			while(!feof($f)){
				$str .= fread($f, 4096);
			}
			fclose($f);
		}
		return $str;
	}

	/**
	* @bref
	*   - 파일용량을 단위별로 표시(2008. 6. 10) 천재 최수영씨 제공
	**/
	public static function getByteView($fs_size, $fs_decimal=''){
		$fs_temp = $fs_size;
		$fs_decimal = ($fs_decimal) ? $fs_decimal : 2;
		$fs_unit = Array(' Byte', ' KByte', ' MByte', ' GByte', ' TByte');
		for($i=0; $i<4; $i++, $fs_temp/=1024) if($fs_temp < 1024) break;
		$fs_number = explode('.', round($fs_temp, $fs_decimal));
		$fs_number[0] = number_format($fs_number[0]);
		return @implode('.', $fs_number) . $fs_unit[$i];
	}

	/**
	* @bref
	*   - 지정한 디렉토리내의 파일 목록 반환
	*   - 모두:'a',폴더:'d',파일:'f'    ext:확장자필터
	**/
	public static function getDirEntry($dir, $kind='a', $ext=''){
		
		if(!is_dir($dir)) return;
		
		if(!($RD = opendir($dir))){
			return false;
		}
		$result = array();
		while($entry = readdir($RD)){
			if($entry != '.' && $entry != '..'){
				if($kind=='d'){
					if(is_dir($dir.'/'.$entry)){
						array_push($result, $entry);
					}
				}else if($kind=='f'){
					if(is_file($dir.'/'.$entry)){
						if($ext){
							$temp = self::filename_parse($entry);
							if($temp['extension']==$ext){
								array_push($result, $entry);
							}
						}else{
							array_push($result, $entry);
						}
					}
				}else{
					array_push($result, $entry);
				}
			}
		}
		sort($result);
		closedir($RD);
		return $result;
	}
	
	/**
	* @bref 파일 퍼미션을 0707 형식으로 리턴한다
	* @return string
	**/
	public static function perm($path){
		return substr(sprintf('%o', fileperms($path)), -4);
	}

}
