<?php

require_once("BaseController.php");

class HomeController extends BaseController{
  public function render() {
    $this->render_template("home.html", array(
      "title" => "Welcome"
    ));
  }
}
