<?php

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
    array_push($this->log, array($turn, $player, $message, $public));
  }
}
