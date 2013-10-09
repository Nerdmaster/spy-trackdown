<?php

/**
 * Class for storing regional constants
 */
class Region {
  const NORTH_AMERICA = "North America";
  const SOUTH_AMERICA = "South America";
  const EUROPE        = "Europe";
  const AFRICA        = "Africa";
  const ASIA          = "Asia";
  const AUSTRALIA     = "Australia";

  public static function get_jump_region($region) {
    $jump_links = array(
      NORTH_AMERICA => AFRICA,
      SOUTH_AMERICA => ASIA,
      EUROPE        => AUSTRALIA,
      AFRICA        => NORTH_AMERICA,
      ASIA          => SOUTH_AMERICA,
      AUSTRALIA     => EUROPE,
    );
    return $jump_links[$region];
  }
}
