<?php

namespace Pingur;

class Collection implements \Iterator {
  
  private $objects = array();
  private $position = 0;
  
  public function add($object) {
    $this->objects[] = $object;
  }
  
  public function size() {
    return count($this->objects);
  }
  
  public function jsonEncode() {
    $serialzed = array();
    foreach ($this->objects as $object) {
      $serialzed[] = $object->jsonEncode();
    }
    return '[' . implode(',', $serialzed) . ']';
  }
  
  public function sort($callable) {
    $this->data = usort($this->objects, $callable);
  }
  
  // iterator functions
  public function rewind() {
    $this->position = 0;
  }

  public function current() {
    return $this->objects[$this->position];
  }

  public function key() {
    return $this->position;
  }

  public function next() {
    ++$this->position;
  }

  public function valid() {
    return isset($this->objects[$this->position]);
  }
}