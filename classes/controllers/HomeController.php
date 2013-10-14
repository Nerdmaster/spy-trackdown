<?php

require_once("BaseController.php");

class HomeController extends BaseController{
  public function render() {
    return $this->render_template("home.html", array(
      "title" => "Welcome"
    ));
  }
}
