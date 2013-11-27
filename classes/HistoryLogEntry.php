<?php

/**
 * Simple data structure to store and filter log entries
 */
class HistoryLogEntry {
  public $turn, $player, $message, $public;

  public function __construct($turn, $player, $message, $public) {
    $this->turn = $turn;
    $this->player = $player;
    $this->message = $message;
    $this->public = $public;
  }

  /**
   * Returns true if the passed-in array (field => value) matches the internal
   * data.  Only fields passed in will be checked.
   */
  public function is_match($fields) {
    foreach($fields as $property => $expected) {
      if (!in_array($property, array("turn", "player", "message", "public"))) {
        return FALSE;
      }
      if ($this->$property != $expected) {
        return FALSE;
      }
    }

    return TRUE;
  }
}
