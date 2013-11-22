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
  }
