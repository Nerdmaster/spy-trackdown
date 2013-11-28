<?php

require_class("HistoryLogEntry");

/**
 * Stores history information for the game
 */
class HistoryLog {
  private $log;

  public function __construct() {
    $this->log = array();
  }

  /**
   * Returns a copy of the log array
   */
  public function log() {
    return $this->log;
  }

  /**
   * Stores a message into the history log
   *
   * @param integer $turn
   *   Turn number of the given message
   *
   * @param object $player
   *   Player who "owns" the message - not very meaningful for public messages, but potentially
   *   useful to filter who did what during a game.
   *
   * @param string $message
   *   Text of the message as it will be displayed to users
   *
   * @param boolean $public
   *   Who can see the message - if true, everybody, otherwise just the player
   */
  public function add($turn, $player, $message, $public) {
    array_push($this->log, new HistoryLogEntry($turn, $player, $message, $public));
  }

  /**
   * Finds specific messages where filter fields match the given values
   *
   * @param string $filters
   *   Array of filters.  Each filter contains a field and value.  All filters are "and"ed, and
   *   must be an exact match.  Valid fields are "turn", "player", and "public".
   *
   * @return array
   *   Array of messages which satisfied the criteria
   */
  public function filter_log_entries($filters) {
    $matches = array();

    foreach ($this->log as $entry) {
      if ($entry->is_match($filters)) {
        array_push($matches, $entry);
      }
    }

    return $matches;
  }

  /**
   * Returns the given player's secret messages for a specific turn
   */
  public function get_secret_messages($player, $turn) {
    $entries = $this->filter_log_entries(array("player" => $player, "turn" => $turn, "public" => false));
    return array_map(function($entry) { return $entry->message; }, $entries);
  }

  /**
   * Returns public messages for the given turn
   */
  public function get_turn_messages($turn) {
    return $this->filter_log_entries(array("turn" => $turn, "public" => true));
  }
}
