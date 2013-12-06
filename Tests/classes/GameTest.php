<?php
  require_once(__DIR__ . "/../../root.php");
  require_class("Game");

  class GameTest extends PHPUnit_Framework_TestCase {
    /**
     * Okay, this is a poor test, but it's sort of a smoke test just to be sure the game as a
     * whole appears to run properly.
     */
    public function testGameAPI() {
      $g = new Game();
      $g->name("Test");
      $g->add_player("Blackfire");
      $g->add_player("Goldhawk");

      $this->assertEquals($g->status(), Game::STATUS_PRE_GAME);

      $g->start();
      $p = $g->current_player();

      $this->assertEquals($g->status(), Game::STATUS_READY_FOR_PLAYER);
      $this->assertEquals($p->name(), "Blackfire");

      $zone = Map::get_zone_by_code($p->location());

      $link = NULL;
      $links = $zone->links();
      $travel_code = -1;
      while (!$link) {
        $travel_code++;
        $link = $links[$travel_code];
      }

      // Start of turn converts action to 1
      $g->start_turn();
      $this->assertEquals($g->current_action(), 1);

      // Player travel should move player to the expected zone we discovered above
      $g->player_travel($travel_code);
      $this->assertEquals($p->location(), $link->code());

      // Action is now 2
      $this->assertEquals($g->current_action(), 2);

      // Player travels back to original zone when using the same travel code
      $g->player_travel($travel_code);
      $this->assertEquals($p->location(), $zone->code());

      // We're now waiting for the player to get their secret message
      $this->assertEquals($g->status(), Game::STATUS_PLAYER_TURN_END);

      // TODO: Test secret message, control switching to new player, and eventually control going
      // back to the first player
    }
  }
