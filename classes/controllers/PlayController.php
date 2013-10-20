<?php

require_once("BaseController.php");
require_class("Game");
require_class("data/Game");

class PlayController extends BaseController{
  protected $action;
  protected $game_store;

  /**
   * Pulls out the id and action from the URL prior to the base controller setting things up
   */
  public function __construct($path_array) {
    $this->action = array_shift($path_array);
    $id = array_shift($path_array);
    $this->game_store = new Data\Game();
    $this->game_store->id($id);
    parent::__construct($path_array);
  }

  public function process() {
    $actions = array("show");
    if (!in_array($this->action, $actions)) {
      $this->http_status = 'HTTP/1.1 404 Not Found';
      $this->text = "Invalid action, " . htmlspecialchars($this->action) .
                    ".  Please double-check your URL and try again.";
      return;
    }

    $this->game_store->load();
    $fn = sprintf("render_%s", $this->action);
    $this->$fn();
  }

  /**
   * Response for GET /play/show - generic handler for a player to take an action.  Looks at
   * game state and uses data to show the spy phone (player hasn't taken all actions yet), show
   * covert messages (player just finished taking actions) or show the "give phone to X"
   * message (next player's turn is starting).
   */
  private function render_show() {
    // Read game turn state
    $this->template = "play/phone";
    $player = $this->game_store->game()->current_player();
    $this->variables = array("title" => "", "player_name" => $player->name());
  }
}
