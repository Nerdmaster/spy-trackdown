<?php
  require_once(__DIR__ . "/../../root.php");
  require_class("HistoryLog");
  require_class("Player");

  class HistoryLogTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
      $this->player1 = new Player("1");
      $this->player2 = new Player("2");
      $this->h = new HistoryLog();
    }

    public function testYouCantAlterLogs() {
      $this->h->add(1, $this->player1, "Test", true);
      $this->assertEquals(1, count($this->h->log()));

      $log = $this->h->log();
      array_push($log, "cheat!");
      $this->assertEquals(2, count($log));
      $this->assertEquals(1, count($this->h->log()));
    }

    public function testItStoresMessages() {
      $this->h->add(1, $this->player1, "This is a public message", true);
      $this->h->add(1, $this->player1, "This is a private message", false);
      $this->assertEquals(2, count($this->h->log()));
    }

    public function test_get_secret_messages() {
      // This is an alias, not a hack
      $h = $this->h;
      $f = function($player, $turn) use($h) {
        return $h->get_secret_messages($player, $turn);
      };

      // It gets the private message
      $h->add(1, $this->player1, "This is a public message", true);
      $h->add(1, $this->player1, "This is a private message", false);
      $p1_expected_messages = array("This is a private message");
      $this->assertEquals($p1_expected_messages, $f($this->player1, 1));

      // It doesn't get the other player's message
      $h->add(1, $this->player2, "This is a public message for player 2", true);
      $h->add(1, $this->player2, "This is a private message for player 2", false);
      $p2_expected_messages = array("This is a private message for player 2");
      $this->assertEquals($p1_expected_messages, $f($this->player1, 1));
      $this->assertEquals($p2_expected_messages, $f($this->player2, 1));

      // It doesn't get the other turn's message for either player
      $h->add(2, $this->player1, "This is a public message for turn 2", true);
      $h->add(2, $this->player1, "This is a private message for turn 2", false);
      $h->add(2, $this->player2, "This is a public message for player 2 for turn 2", true);
      $h->add(2, $this->player2, "This is a private message for player 2 for turn 2", false);
      $this->assertEquals($p1_expected_messages, $f($this->player1, 1));
      $this->assertEquals($p2_expected_messages, $f($this->player2, 1));
    }

    public function test_get_turn_messages() {
      // This is an alias, not a hack
      $h = $this->h;
      $f = function($turn) use($h) {
        return $h->get_turn_messages($turn);
      };

      // It gets all public messages for the given turn
      $h->add(1, $this->player1, "t1p1", true);
      $h->add(1, $this->player1, "t1p1-priv", false);
      $h->add(1, $this->player2, "t1p2", true);
      $h->add(1, $this->player2, "t1p2-priv", false);
      $h->add(2, $this->player1, "t2p1", true);
      $h->add(2, $this->player1, "t2p1-priv", false);
      $h->add(2, $this->player2, "t2p2", true);
      $h->add(2, $this->player2, "t2p2-priv", false);

      $entries = $f(1);
      $this->assertEquals(2, count($entries));
      foreach ($entries as $entry) {
        $this->assertEquals(1, $entry->turn);
        $this->assertEquals(true, $entry->public);
      }

      $entries = $f(2);
      $this->assertEquals(2, count($entries));
      foreach ($entries as $entry) {
        $this->assertEquals(2, $entry->turn);
        $this->assertEquals(true, $entry->public);
      }
    }
  }
