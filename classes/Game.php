<?php

require_class("Map");
require_class("Player");

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

  /** Game status flags */
  private $started, $completed;

  /** Array of region => covert op map zone */
  private $covert_ops_locations;

  /** Map zone for mastermind */
  private $mastermind_location;

  public function __construct() {
    $this->name = "Generic Game";
    $this->players = array();
    $this->started = FALSE;
    $this->completed = FALSE;
    $this->current_turn = 0;
    $this->current_action = 0;
  }

  /**
   * Adds the given player to the game - only for games that haven't started yet
   */
  public function add_player($name) {
    if ($this->started) {
      throw new Exception("Cannot add players to a game already in progress!");
    }

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
   * Starts a new game, initializing random locations for mastermind, players, and covert agents
   */
  public function start() {
    if (count($this->players) == 0) {
      throw new Exception("Cannot start a game without players!");
    }

    if ($this->started) {
      throw new Exception("Cannot start a game already in progress!");
    }

    $this->started = TRUE;

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
  }
}
