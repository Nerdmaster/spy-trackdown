<?php

/**
 * MapZone display class
 */
class MapZoneDisplay {
  private $zone;

  public function __construct($zone) {
    $this->zone = $zone;
  }

  public function name() {
    $region = $this->zone->region();
    $loc = $this->zone->location_number();
    $city = $this->zone->city_name();
    return sprintf("%s - %d (%s)", $region, $loc, $city);
  }
}
