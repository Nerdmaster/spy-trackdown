<?php

/**
 * Class for housing travel constants
 */
class Travel {
  const JET = "Jet";
  const MOTORCYCLE = "Motorcycle";
  const SPORTS_CAR = "Sports car";
  const HELICOPTER = "Helicopter";

  // Easier aliases for just looking at the map
  const RED = self::SPORTS_CAR;
  const BLUE = self::HELICOPTER;
  const GREEN = self::MOTORCYCLE;
  const YELLOW = self::JET;
}
