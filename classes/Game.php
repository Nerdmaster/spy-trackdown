<?php

require_class("Map");
require_class("Player");
require_once(ROOT . '/classes/errors.php');

/**
 * Holds all top-level data necessary to run a game.  Violates SRP like mad for simplicity
 */
class Game {
  /** Unique name to identify a game in case cookie is lost or something */
  private $name;

  /** Which players are in the game and how they're identified */
  private $players;

  /** What is the current turn number? */
  private $current_turn;

  /** What is the current action number? (1 or 2) */
  private $current_action;

  /** Whose turn is it (index into players array)? */
  private $current_player;

  /**
   * Game status - are we waiting on a player to take an action?  Waiting to give a secret
   * message?  Ready for the next player?
   */
  private $status;
  const STATUS_PRE_GAME = 0;
  const STATUS_READY_FOR_PLAYER = 1;
  const STATUS_AWAITING_ACTION = 2;
  const STATUS_AWAITING_SECRET_MESSAGE = 3;
  const STATUS_GAME_OVER = 4;

  /** Array of region => covert op map zone */
  private $covert_ops_locations;

  /** Map zone for mastermind */
  private $mastermind_location;

  public function __construct() {
    $this->name = "Generic Game";
    $this->players = array();
    $this->status = self::STATUS_PRE_GAME;
    $this->current_turn = 0;
    $this->current_action = 0;
  }

  /**
   * Throws WorkflowStatusError when the current status doesn't match the allowed status
   */
  private function validate_status($allowed_status, $message = "Invalid status") {
    if ($this->status != $allowed_status) {
      throw new WorkflowStatusError("$message (Status: {$this->status}");
    }
  }

  /**
   * Adds the given player to the game - only for games that haven't started yet
   */
  public function add_player($name) {
    $this->validate_status(self::STATUS_PRE_GAME, "Players can only be added before the game begins!");
    $this->players[] = new Player($name);
  }

  /**
   * Gets and optionally sets a name for identification of this game if multiple games are being
   * played in one installation instance.
   */
  public function name($name = NULL) {
    if ($name) {
      $this->name = $name;
    }
    return $this->name;
  }

  /**
   * Returns current player object (not the index in $this->current_player)
   */
  public function current_player() {
    return $this->players[$this->current_player];
  }

  /**
   * Returns current turn number
   */
  public function current_turn() {
    return $this->current_turn;
  }

  /**
   * Returns current action
   */
  public function current_action() {
    return $this->current_action;
  }

  /**
   * Returns current game status
   */
  public function status() {
    return $this->status;
  }

  /**
   * Starts a new game, initializing random locations for mastermind, players, and covert agents
   */
  public function start() {
    $this->validate_status(self::STATUS_PRE_GAME, "Cannot start a game that's already been started!");

    if (count($this->players) == 0) {
      throw new Exception("Cannot start a game without players!");
    }

    // Set up players, mastermind, and covert ops locations
    $all_zones = Map::zones();
    $this->covert_ops_locations = array();
    $this->mastermind_location = $all_zones[array_rand($all_zones)]->code();

    foreach (Region::regions() as $region) {
      $zones = Map::zones($region);
      // TODO: Remove this hack once map data is fully populated and verified
      if (count($zones) == 0) {
        continue;
      }
      $code = $zones[array_rand($zones)]->code();
      $this->covert_ops_locations[$region] = $code;
    }

    // Set up player location
    $player_location = $all_zones[array_rand($all_zones)]->code();
    foreach ($this->players as $player) {
      $player->location($player_location);
    }

    // Set up turn
    $this->current_turn = 1;
    $this->current_action = 1;
    $this->current_player = 0;
    $this->status = self::STATUS_READY_FOR_PLAYER;
  }

  /**
   * Transition: player has acknowledged start of his turn
   */
  public function start_turn() {
    $this->validate_status(self::STATUS_READY_FOR_PLAYER, "Cannot start turn from this status");
    $this->status = self::STATUS_AWAITING_ACTION;
  }

  /**
   * Moves a player by the given travel method.
   *
   * TODO: Split this up into more "do one thing" functions :(
   */
  public function player_travel($method) {
    $this->validate_status(self::STATUS_AWAITING_ACTION, "Cannot travel from this status");

    $player = $this->current_player();
    $zone = Map::get_zone_by_code($player->location());

    $travel_text = Travel::$text[$method];
    if (!$travel_text) {
      throw new InvalidTravelError("Invalid travel code used, cannot attempt travel");
    }

    $new_zone = $zone->link($method);
    if (!$new_zone) {
      throw new InvalidTravelError("Cannot travel from here via $travel_text");
    }

    // All is well - move the player and end this action
    $player->location($new_zone->code());
    $this->end_action();
  }

  /**
   * Ends a player's action.  If it's their last action, sets internal status to indicate it's
   * time for their secret message.
   */
  private function end_action() {
    $this->current_action++;

    if ($this->current_action > 2) {
      $this->status = self::STATUS_AWAITING_SECRET_MESSAGE;
    }
  }
}
