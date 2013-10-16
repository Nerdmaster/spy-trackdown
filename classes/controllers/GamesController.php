<?php

require_once("BaseController.php");

class GamesController extends BaseController{
  protected $action;

  /**
   * Pulls out the action from the URL prior to the base controller setting things up
   */
  public function __construct($path_array) {
    $this->action = array_shift($path_array);
    parent::__construct($path_array);
  }

  public function process() {
    $actions = array("new", "login");
    if (!in_array($this->action, $actions)) {
      $this->http_status = 'HTTP/1.1 404 Not Found';
      $this->text = "Invalid action, " . htmlspecialchars($this->action) .
                    ".  Please double-check your URL and try again.";
      return;
    }

    $fn = sprintf("render_%s", $this->action);
    $this->$fn();
  }

  private function render_new() {
    $this->template = "games/new_form.html";
    $this->variables = array("title" => "New Game");
  }

  private function render_login() {
    $this->template = "games/login_form.html";
    $this->variables = array("title" => "Resume Game");
  }
}
