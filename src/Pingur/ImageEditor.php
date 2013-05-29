<?php

namespace Pingur;

class ImageEditor {
  
  public static function createThumbnail($src, $dst, $max_height = 200, $max_width = 200) {
    list($width, $height) = getimagesize($src);    
    $i_src = imagecreatefromstring(file_get_contents($src));
    $f_width = $max_width;
    $f_height = $height * ($f_width / $width);
    if ($f_height > $max_height) {
      $f_height = $max_height;
      $f_width = $width * ($f_height / $height);
    }
    $i_dst = imagecreatetruecolor($f_width, $f_height);
    imagecopyresampled($i_dst, $i_src, 0, 0, 0, 0, $f_width, $f_height, $width, $height);
    imagedestroy($i_src);
    
    $ext = strtolower(substr($dst, strrpos($dst, '.')));
    switch ($ext) {
      case '.gif':
          imagegif($i_dst, $dst);
          break;
      case '.jpg':
      case '.jpeg':
          imagejpeg($i_dst, $dst);
          break;
      case '.png':
          imagepng($i_dst, $dst);
          break;
      case '.bmp':
          imagewbmp($i_dst, $dst);
          break;
      default:
        imagedestroy($i_dst);
        return false;
    }
    imagedestroy($i_dst);
    return $dst;
  }
}