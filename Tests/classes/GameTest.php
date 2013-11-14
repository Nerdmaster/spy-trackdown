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

      $this->assertEquals($g->status(), Game::STATUS_READY_FOR_PLAYER);
      $this->assertEquals($g->current_player()->name(), "Blackfire");

      // TODO: Move player, make sure turn changes, etc.
    }
  }
