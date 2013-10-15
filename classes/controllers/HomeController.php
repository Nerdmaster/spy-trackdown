<?php

require_once("BaseController.php");

class HomeController extends BaseController{
  public function process() {
    $this->template = "home.html";
    $this->variables = array(
      "title" => "Welcome"
    );
  }
}
