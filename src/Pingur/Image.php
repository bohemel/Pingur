<?php

namespace Pingur;

class Image {
  
  private static $db = FALSE;

  public function __construct($data) {
    $this->data = $data;
  }
  
  public function jsonEncode() {
    return json_encode($this->data);
  }
  
  public function __get($name) {
    if (array_key_exists($name, $this->data)) {
      return $this->data[$name];
    }

    $trace = debug_backtrace();
    trigger_error(
      'Undefined property via __get(): ' . $name .
      ' in ' . $trace[0]['file'] .
      ' on line ' . $trace[0]['line'],
      E_USER_NOTICE);
    return null;
  }
  
  public static function createFromFilesArray() {
    $collection  = new Collection();
    foreach ($_FILES['files']['name'] as $i => $value) {
      
      if (strpos($_FILES['files']['type'][$i], 'image') === FALSE)
        break;
      
      $ext = strtolower(substr($_FILES['files']['name'][$i], strrpos($_FILES['files']['name'][$i], '.')));
      
      // create filenames
      $final_filename = Config::read('image_data_dir') . DIRECTORY_SEPARATOR .
        uniqid() . $ext;
      $thumb_filename = dirname($final_filename) . DIRECTORY_SEPARATOR . 
        't' . DIRECTORY_SEPARATOR . basename($final_filename);
      
      move_uploaded_file($_FILES['files']['tmp_name'][$i], $final_filename);
      
      // create thumbnail
      ImageEditor::createThumbnail($final_filename, $thumb_filename);
      
      $collection->add(new self(array(
        'filename' => $final_filename,
        'thumbnail' => $thumb_filename,
      )));
    }
    return $collection;
  }
  
  static function search($args = NULL) {
    $collection  = new Collection();
    foreach (glob(Config::read('image_data_dir'). DIRECTORY_SEPARATOR . '*') as $file) {
      if (!is_dir($file))
        $collection->add(new self(array(
          'filename' => $file,
          'thumbnail' => Config::read('image_data_dir') . DIRECTORY_SEPARATOR . 't' . DIRECTORY_SEPARATOR . basename($file),
        )));
    }
    $collection->sort(function($a, $b) {
      return filemtime($a->filename) < filemtime($b->filename);
    });
    return $collection;
  }
}