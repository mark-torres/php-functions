<?php
function get_scale($src_pic, $targ_width, $targ_height)
{
	$targ_width  = abs($targ_width);
	$targ_height = abs($targ_height);
	if(($targ_width * $targ_height) == 0)
		return false;
	if(!is_readable($src_pic)) return false;
	list($src_width, $src_height, $type) = getimagesize($src_pic);
	$width_ratio = $src_width / $targ_width;
	$height_ratio = $src_height / $targ_height;
	$ratio = ($width_ratio >= $height_ratio) ? $width_ratio : $height_ratio;
	$targ_size = array();
	$targ_size['width'] = round($src_width / $ratio);
	$targ_size['height'] = round($src_height / $ratio);
	return $targ_size;
} // - - end of get_scale - - - - -

function pic_scale($src_pic, $targ_width, $targ_height, $targ_pic = false)
{
	$targ_width  = abs($targ_width);
	$targ_height = abs($targ_height);
	if(($targ_width * $targ_height) == 0)
		return false;
	if(!is_readable($src_pic)) return false;
	list($src_width, $src_height, $type) = getimagesize($src_pic);
	$width_ratio = $src_width / $targ_width;
	$height_ratio = $src_height / $targ_height;
	$ratio = ($width_ratio >= $height_ratio) ? $width_ratio : $height_ratio;
	$targ_size = array();
	$targ_size['width'] = round($src_width / $ratio);
	$targ_size['height'] = round($src_height / $ratio);
	$src_info = pathinfo($src_pic);
	list($dirname, $basename, $ext, $filename) = array_values($src_info);
	if(empty($targ_pic))
	{
		$targ_pic = "$dirname/{$filename}_scaled.{$ext}";
	}
	$mime_type = image_type_to_mime_type($type);
	$pic_handler = false;
	switch($mime_type)
	{
		case 'image/gif':
			$pic_handler = imagecreatefromgif($src_pic);
			break;
		case 'image/jpeg':
			$pic_handler = imagecreatefromjpeg($src_pic);
			break;
		case 'image/png':
			$pic_handler = imagecreatefrompng($src_pic);
			break;
		default: $pic_handler = false;
	}
	if($pic_handler)
	{
		$new_image = imagecreatetruecolor($targ_size['width'], $targ_size['height']);
		imagecopyresized($new_image, $pic_handler, 
			0, 0, 0, 0,
			$targ_size['width'], $targ_size['height'], $src_width, $src_height);
		// save image
		switch($mime_type)
		{
			case 'image/gif':
				imagegif($new_image, $targ_pic);
				break;
			case 'image/jpeg':
				imagejpeg($new_image, $targ_pic);
				break;
			case 'image/png':
				imagepng($new_image, $targ_pic);
				break;
		}
		imagedestroy($new_image);
		imagedestroy($pic_handler);
		return file_exists($targ_pic);
	}
	else
	{
		return false;
	}
} // - - end of pic_scale_box - - - - -
