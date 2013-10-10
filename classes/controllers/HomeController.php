<?php

class HomeController {
  public function __construct($path_array) {
  }

  public function render() {
    print "<h1>" . APPNAME . "</h1>\n";
    print "Welcome!";
  }
}
