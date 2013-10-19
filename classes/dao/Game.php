<?php

namespace Data;

/**
 * Data access class to serialize and deserialize game data - right now this is horrible and done
 * by literally just calling serialize and deserialize, but we put it all here so when it's DB-
 * or redis-based, it's just a fix to this one file instead of all over the app.
 */
class Game {
  public function save_game($game) {
    $id = sha1(SERVER_SECRET . $game->name());
    $fh = fopen(ROOT . "/data/games/$id", "w");
    fwrite($fh, serialize($game));
    fclose($fh);
  }
}
