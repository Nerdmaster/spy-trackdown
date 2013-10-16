<?php

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

  /** Game status flags */
  private $started, $completed;

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

    // TODO: Use player objects of some sort here, not just names!
    $this->players[] = $name;
  }

  /**
   * Sets a name for identification of this game if multiple games are being played in one app
   */
  public function set_name($name) {
    $this->name = $name;
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

    // TODO: Set up mastermind location

    // TODO: Set up covert agent location

    // TODO: Set up player start location - cannot be the same as any covert agents' locations
  }
}
