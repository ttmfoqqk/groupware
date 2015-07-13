<?php
/**
* @file ImageUtil.php
*
* @class ImageUtil
*
* @bref 이미지 관련 유틸리티
*
* @date 기억안남
*
* @author 너구리안주(impactlife@naver.com)
*
* @license 이 주석(클래스 명세주석)을 지우지 않는 한 Free
*
* @section MODIFYINFO
* 	- 2011.03.26/권혁준
*	- 썸네일기능 클래스로 분리해 나감(Thumbnail.php)
*	- getInfo9 추가
*	- 부분부분 몇가지 수정함
*
* @section Example
*   - 없음
*/

/**
* @bref
*   - 보조 클래스 (9 slice 정보를 위한 dto)
**/
class Info9{
	public $unit_w = 0;
	public $unit_h = 0;
	public $items = array();
}

class Imageutil{

	/**
	* @bref
	*   - 똑같은 이미지에 대해 getimagesize 함수를 여러번 호출하는걸 방지하기 위해 싱글턴 구현
	**/
	public static function getImageSize($imgpath){
		static $arr = array();
		for($i=0;$i<count($arr);$i++){
			if($arr[$i]["imgpath"] == $imgpath){
				return $arr[$i];
			}
		}
		$idx = count($arr);
		$arr[$idx] = getimagesize($imgpath);
		$arr[$idx]["imgpath"] = $imgpath;
		return $arr[$idx];
	}

	/**
	* @bref
	*   - 가로나 세로의 크기를 지정된 크기 이하의 수치로 리턴함
	**/
	public static function rateLimit($imgpath, $max_w="", $max_h=""){
		$arr = array();
		$rate = 1; //축소 비율
		list($img_w, $img_h) = self::getImageSize($imgpath);

		//가로길이 조정
		if((int)$max_w > 0){
			if($img_w > $max_w){
				$rate = $max_w / $img_w;
				$img_w = $max_w;
				$img_h = $img_h * $rate; //round($img[1] * $rate);
			}
		}

		if((int)$max_h > 0){
			if($img_h > $max_h){
				$rate = $max_h / $img_h;
				$img_w = $img_w * $rate;
				$img_h = $max_h;
			}
		}

		return array($img_w, $img_h, $rate);
	}

	/**
	* @bref
	*   - 리소스로부터 이미지 출력
	**/
	public static function imageOutput($const_type, $img_res, $save_path="", $quality=75, $filter=""){

		switch($const_type){
			case IMAGETYPE_GIF :
				if(trim($save_path)=="")header("Content-type: image/gif");
				//ImageInterlace($img_res, true);
				imagegif($img_res, $save_path);
				break;

			case IMAGETYPE_JPEG :
				if(trim($save_path)=="")header("Content-type: image/jpeg");
				//ImageInterlace($img_res, true);
				imagejpeg($img_res, $save_path, $quality);
				break;

			case IMAGETYPE_PNG :
			    $quality = $quality / 10;
			    $quality = $quality > 9 ? $quality - 1 : $quality;

				if(trim($save_path)=="")header("Content-type: image/png");
				//ImageInterlace($img_res, true);
				imagepng($img_res, $save_path, $quality, $filter);
				break;
		}
	}

	/**
	* @bref
	*   - 이미지파일인지 체크(1:gif, 2:jpg, 3:png), 3가지 타입 외에는 모두 아닌걸로 함
	**/
	public static function isImageFile($imgpath){
		$info = self::getImageSize($imgpath);
		if($info[2] == IMAGETYPE_GIF || $info[2] == IMAGETYPE_JPEG || $info[2] == IMAGETYPE_PNG){
			return true;
		}else{
			return false;
		}
	}

	/**
	* @bref
	*   - 빈 이미지를 만들어서 리소스 리턴.  is_transparent 가 true 이면 투명
	**/
	public static function makeBlankImage($width, $height, $bgcolor="000000", $is_transparent=false){
		$img = imagecreatetruecolor($width, $height);
		//imagealphablending($img, false);
		//imageinterlace($img, true);

		if(substr($bgcolor, 0, 1) == "#") $bgcolor = substr($bgcolor, 1);
		$r = hexdec(substr($bgcolor, 0, 2));
		$g = hexdec(substr($bgcolor, 2, 2));
		$b = hexdec(substr($bgcolor, 4, 2));

		if($is_transparent){
			$color = imagecolorallocate($img, $r, $g, $b);
			imagecolortransparent($img, $color);
			//$color = imagecolorclosest($img, $r, $g, $b);
			//imagecolortransparent($img, $color);
		}
		return $img;
	}

	/**
	* @bref
	*   - 이미지경로로부터 이미지만들기
	**/
	public static function imageCreateFromPath($img_path){
		$temp = self::getImageSize($img_path);
		switch($temp[2]){
		case IMAGETYPE_GIF :
			return imagecreatefromgif($img_path);
			break;
		case IMAGETYPE_JPEG :
			return imagecreatefromjpeg($img_path);
			break;
		case IMAGETYPE_PNG :
			return imagecreatefrompng($img_path);
			ImageInterlace($img_res, true);
			break;
		default :
			return;
		}
	}

	/**
	* @bref
	*   - 이미지를 9등분으로 나눠서 정보 리턴
	*   - 단위너비, 단위높이 각 시작위치 x,y 값, 블록당 수평/수직 정렬값 리턴
	**/
	public static function getInfo9($image){

		$info = new Info9();

		if(is_resource($image)){
			$w = ImageSX($image);
			$h = ImageSY($image);
		}else{
			list($w, $h) = self::getImageSize($image);
		}
		$info->unit_w = $w / 3;
		$info->unit_h = $h / 3;

		$k=0;
		for($i=0;$i<3;$i++){
			for($j=0;$j<3;$j++){
				$info->items[$k] = array();
				$info->items[$k]["left"] = $info->unit_w * $j;
				$info->items[$k]["top"] = $info->unit_h * $i;

				//해당 블록의 수평정렬 위치 구하기
				switch(($k+1)%3){
				case 0:
					$info->items[$k]["align"] = "right";
					break;
				case 1:
					$info->items[$k]["align"] = "left";
					break;
				case 2:
					$info->items[$k]["align"] = "center";
					break;
				}

				//해당 블록의 수직정렬 위치 구하기
				switch(($k-($k % 3)) / 3){
				case 0:
					$info->items[$k]["valign"] = "top";
					break;
				case 1:
					$info->items[$k]["valign"] = "middle";
					break;
				case 2:
					$info->items[$k]["valign"] = "bottom";
					break;
				}

				$k++;
			}
		}
		return $info;
	}
	
	/**
	* @bref 애니메이션 Gif인지 체크 (http://www.php.net/manual/en/function.imagecreatefromgif.php#104473)
	* @param string 파일경로
	* @return
	**/
	public static function isAnimatedGif($filepath) {
	    if(!($fh = @fopen($filepath, 'rb'))) return false;
	    $count = 0;
	    //an animated gif contains multiple "frames", with each frame having a
	    //header made up of:
	    // * a static 4-byte sequence (\x00\x21\xF9\x04)
	    // * 4 variable bytes
	    // * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)
	   
	    // We read through the file til we reach the end of the file, or we've found
	    // at least 2 frame headers
	    while(!feof($fh) && $count < 2) {
	        $chunk = fread($fh, 1024 * 100); //read 100kb at a time
	        $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
	   	}
	   
		fclose($fh);
		return $count > 1;
	}
}