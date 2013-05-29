<?php

namespace Pingur;

class Image {

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
      
      $final_filename = Config::read('image_data_dir')
        . DIRECTORY_SEPARATOR . preg_replace('/[^a-zA-Z0-9-_.]/', '', $_FILES['files']['name'][$i]);
      $thumb_filename = Config::read('image_data_dir') . DIRECTORY_SEPARATOR . 't'
        . DIRECTORY_SEPARATOR . preg_replace('/[^a-zA-Z0-9-_.]/', '', $_FILES['files']['name'][$i]);
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
    return $collection;
  }
}