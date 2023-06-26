<?php
//file upload.php

ini_set("display_errors",0);

define("MAX_SIZE", "400");

function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; } 
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}

$fileName		= $_FILES['picture']['name'];
$fileSize		= $_FILES['picture']['size'];
$fileError		= $_FILES['picture']['error'];
$uploadedFile 	= $_FILES['picture']['tmp_name'];
$fileExt		= getExtension($fileName);
$fileExt		= strtolower($fileExt);

$element	= $_GET['frame'];
$success 	= false;
$alert 		= '';

if($fileSize > 0 || $fileError == 0){
	@move_uploaded_file($uploadedFile,'tmp_pic/'.$element.'_'.$fileName);
	/*if (($fileExt != "jpg") && ($fileExt != "jpeg") && ($fileExt != "png") && ($fileExt != "gif")){
		$alert = 'File extension not supported!';
		$success = 0; 
	}else{
		if ($fileSize > MAX_SIZE * 1024){
			$alert = 'File size is too big!';
			$success = 0; 
		}else{
			if($fileExt == "jpg" || $fileExt == "jpeg") { 
				$src = imagecreatefromjpeg($uploadedFile);
				//if($src===FALSE) $alert="error jpeg convert";
			}else if($fileExt == "png"){ 
				$src = imagecreatefrompng($uploadedFile);
				//if($src===FALSE) $alert="error png convert";
			}else{ 
				$src = imagecreatefromgif($uploadedFile); 
				//if($src===FALSE) $alert="error gif convert";
			}
			
			list($width, $height) = getimagesize($uploadedFile);			
			$newwidth	= 360;
			$newheight 	= ($height / $width) * $newwidth;
			$alert=$newheight." - ".$newwidth;
			$tmp       	= imagecreatetruecolor($newwidth, $newheight);
			//if($tmp===FALSE) $alert="error create true color";
			$copy = imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			if($copy===FALSE) $alert("error copy sampled");
			$filename  = 'tmp_pic/'.$element.'_'.$fileName;
			if($fileExt == "jpg" || $fileExt == "jpeg") { 
				$draw=imagejpeg($tmp, $filename, 100);
				//if($draw===FALSE) $alert="error jpeg create";
			}else if($fileExt == "png"){ 
				$draw=imagepng($tmp, $filename, 100);
				//if($draw===FALSE) $alert="error png create";
			}else{ 
				$draw=imagegif($tmp, $filename, 100);
				//if($draw===FALSE) $alert="error gif create";
			}
			imagedestroy($src);
			imagedestroy($tmp);
			//$alert="Success";
			$success = 1;
		}
	}*/
}

if($element=='header' or $element=='footer'){
	$w=180;
	$h=60;
}else if($element=='header-background' or $element=='footer-background' or $element=='bg_picture'){
	$w=200;
	$h=100;
}else if($element=='landing' or $element=='confirmation' or $element=='thankyou' or $element=='info'){
	$w=200;
	$h=200;
}else if($element=='catalogb'){
	$w=100;
	$h=100;
}
?>
<script type="text/javascript">
<?php
	echo "parent.pesan='$alert';";
	echo "parent.displayPicture('tmp_pic/".$element."_"."$fileName','$element',$w,$h);";
?>
</script>