<?php

require_once("BaseController.php");
require_class("Game");
require_class("data/Game");
require_class("displays/MapZone");
require_class("errors");

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
    $actions = array("show", "action_start_turn", "action_travel");
    if (!in_array($this->action, $actions)) {
      $this->http_status = 'HTTP/1.1 404 Not Found';
      $this->text = "Invalid action, " . htmlspecialchars($this->action) .
                    ".  Please double-check your URL and try again.";
      return;
    }

    $this->extract_data_from_store();

    $fn = sprintf("render_%s", $this->action);
    $this->$fn();
  }

  /**
   * Loads the game_store data and extracts data into variables to simplify common functionality
   */
  private function extract_data_from_store() {
    $this->game_store->load();
    $this->game = $this->game_store->game();
    $this->player = $this->game->current_player();
    $this->action_num = $this->game->current_action();
    $this->zone = Map::get_zone_by_code($this->player->location());

    $this->variable("title", $this->player->name() . "'s turn");
    $this->variable("player_name", $this->player->name());
    $this->variable("zone", new MapZoneDisplay($this->zone));
    $this->variable("turn", $this->game->current_turn());
    $this->variable("game_id", $this->game_store->id());
    $this->variable("action_ordinal", $this->get_action_ordinal($this->action_num));
  }

  private function get_action_ordinal($num) {
    switch($num) {
      case 1:
        return "First";
      case 2:
        return "Second";
      default:
        return "UNKNOWN!";
    }
  }

  /**
   * Response for GET /play/show - generic handler for a player to take an action.  Looks at
   * game state and uses data to show the spy phone (player hasn't taken all actions yet), show
   * covert messages (player just finished taking actions) or show the "give phone to X"
   * message (next player's turn is starting).
   */
  private function render_show() {
    switch($this->game->status()) {
      case Game::STATUS_READY_FOR_PLAYER:
        $this->template = "play/ready";
        break;

      case Game::STATUS_AWAITING_ACTION:
        $this->template = "play/phone";
        break;

      case Game::STATUS_PLAYER_TURN_END:
        throw new Exception("Not implemented!");
        break;

      case Game::STATUS_GAME_OVER:
        throw new Exception("Not implemented!");
        break;

      default:
        throw new Exception("Unknown game status when rendering: " . $this->game->status());
    }
  }

  /**
   * Response for POST /play/action_start_turn - simply moves game state forward
   */
  private function render_action_start_turn() {
    $this->game->start_turn();
    $this->game_store->save();
    $this->redirect_to("/play/show/{$this->game_store->id()}");
  }

  /**
   * Response for POST /play/action_travel - handler for all submitted travel actions
   */
  private function render_action_travel() {
    try {
      $this->game->player_travel((integer)$_POST["action"]);
    }
    catch (InvalidTravelError $e) {
      $this->template = "play/phone";
      $this->variable("errors", array($e->getMessage()));
      return;
    }
    catch (Exception $e) {
      error_log($e);
      $this->template = "play/phone";
      $this->variable("errors", array("Unknown error occurred - please try again"));
      return;
    }

    $this->game_store->save();
    $this->redirect_to("/play/show/{$this->game_store->id()}");
  }
}
