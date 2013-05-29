<?php

namespace Pingur;

class Config {
  
  private static $data = array(
    'image_data_dir' => 'i'
  );
  
  public static function read($key) {
    if (isset(static::$data[$key]))
      return static::$data[$key];
    return NULL;
  }
}