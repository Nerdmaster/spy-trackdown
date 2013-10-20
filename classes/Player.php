<?php

/**
 * Simple class to reflect player attributes
 */
class Player {
  private $name;
  private $location;

  public function __construct($name) {
    $this->name = $name;
  }

  public function location($loc = NULL) {
    if (NULL !== $loc) {
      $this->location = $loc;
    }

    return $this->location;
  }

  public function name() {
    return $this->name;
  }
}
