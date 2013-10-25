<?php

/**
 * Class for housing travel constants
 */
class Travel {
  const SPORTS_CAR = 0;
  const HELICOPTER = 1;
  const MOTORCYCLE = 2;
  const JET = 3;

  // Easier aliases for just looking at the map
  const RED = self::SPORTS_CAR;
  const BLUE = self::HELICOPTER;
  const GREEN = self::MOTORCYCLE;
  const YELLOW = self::JET;

  public static $text;
}

Travel::$text = array(
  Travel::SPORTS_CAR => "Sports car",
  Travel::HELICOPTER => "Helicopter",
  Travel::MOTORCYCLE => "Motorcycle",
  Travel::JET => "Jet",
);
