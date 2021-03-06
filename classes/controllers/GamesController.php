<?php

require_once("BaseController.php");
require_class("Game");
require_class("data/Game");
require_class("Utils");

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
    $actions = array("new", "login", "start_game", "intro");
    if (!in_array($this->action, $actions)) {
      $this->http_status = 'HTTP/1.1 404 Not Found';
      $this->text = "Invalid action, " . htmlspecialchars($this->action) .
                    ".  Please double-check your URL and try again.";
      return;
    }

    $fn = sprintf("render_%s", $this->action);
    $this->$fn();
  }

  /**
   * Response for GET /games/new - renders the form to start a game
   */
  private function render_new() {
    $this->template = "games/new_form";
    $this->variable("title", "New Game");
  }

  /**
   * Response for GET /games/login - renders the form to resume a game
   */
  private function render_login() {
    $this->template = "games/login_form";
    $this->variable("title", "Resume Game");
  }

  /**
   * Response for GET /games/start_game - form was submitted to start a game, so we create a new
   * game instance and have it set up data and save to the db.  Then we redirect to start playing.
   */
  private function render_start_game() {
    $game = new Game();

    // This dynamic logic allows us to eventually let the user specify player names - I don't know
    // if that'll ever be necessary, but it doesn't hurt any to have the option
    foreach($_POST["players"] as $player) {
      $game->add_player($player);
    }

    $game->name(Utils::generate_name());

    try {
      $game->start();
    }
    catch (Exception $e) {
      $this->template = "games/new_form";
      $this->variable("title", "New Game");
      $this->variable("errors", array($e->getMessage()));
      return;
    }

    // Save data
    $game_store = new Data\Game();
    $game_store->game($game);
    $game_store->save();

    // Redirect user to start-of-game page
    $this->redirect_to("/games/intro/{$game_store->id()}");
  }

  /**
   * Response for GET /games/intro/:id - game is created and we just need to give players the name
   * and any basic instructions they need
   */
  private function render_intro() {
    $id = array_pop($this->path_array);
    $game_store = new Data\Game();
    $game_store->id($id);
    $game = $game_store->load();
    if (!$game) {
      $this->http_status = "HTTP/1.1 404 Not Found";
      $this->text = "Unable to find the specified game.";
      return;
    }

    $this->template = "games/intro";
    $this->variable("title", "Introduction");
    $this->variable("game_name", $game->name());
    $this->variable("game_id", $id);
  }
}
