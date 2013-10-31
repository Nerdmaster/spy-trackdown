<?php

require_once("BaseController.php");

class HomeController extends BaseController{
  public function process() {
    $this->template = "home";
    $this->variable("title", "Welcome");
  }
}
