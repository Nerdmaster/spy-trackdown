<?php

namespace Data;

/**
 * Data access class to serialize and deserialize game data - right now this is horrible and done
 * by literally just calling serialize and unserialize, but we put it all here so when it's DB-
 * or redis-based, it's just a fix to this one file instead of all over the app.
 */
class Game {
  private $game, $id;

  public function file_path() {
    return ROOT . "/data/games/" . $this->id;
  }

  public function game($game = NULL) {
    if ($game) {
      $this->game = $game;
      $this->id = sha1(SECRET_KEY . $game->name());
    }
    return $this->game;
  }

  public function id($id = NULL) {
    if ($id) {
      $this->id = $id;
    }
    return $this->id;
  }

  public function save() {
    file_put_contents($this->file_path(), serialize($this->game));
  }

  public function load() {
    $this->game = unserialize(file_get_contents($this->file_path()));
    return $this->game;
  }
}
