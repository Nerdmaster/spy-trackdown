<?php

class MapZone {
  private $region;
  private $city_name;
  private $location_number;
  private $links;

  /**
   * Sets up a new MapZone instance with the given data
   */
  public function __construct($region, $city_name, $location_number) {
    $this->region = $region;
    $this->city_name = $city_name;
    $this->location_number = $location_number;

    // Default all links to null
    $this->links = array(
      Travel::JET         => NULL,
      Travel::SPORTS_CAR  => NULL,
      Travel::HELICOPTER  => NULL,
      Travel::MOTORCYCLE  => NULL,
    );
  }

  /**
   * Returns the region in which this zone exists
   */
  public function region() {
    return $this->region;
  }

  /**
   * Returns the zone's city name
   */
  public function city_name() {
    return $this->city_name;
  }

  /**
   * Returns the zone's location number as it appears on the game board
   */
  public function location_number() {
    return $this->location_number;
  }

  /**
   * Returns the link for the given travel method
   *
   * @param integer $travel
   *   Travel constant
   *
   * @return MapZone
   */
  public function link($travel) {
    return $this->links[$travel];
  }

  /**
   * Sets a two-way travel link.  If either zone already has a link for the given method of
   * travel, throws an UnexpectedValueException.
   *
   * @param integer $travel
   *   Travel constant
   *
   * @param MapZone $zone
   *   Zone to link
   */
  public function set_link($travel, $zone) {
    if ($this->links[$travel]) {
      throw new InvalidArgumentException("Attempting to overwrite $travel link for {$this->code()}");
    }
    if ($zone->links[$travel]) {
      throw new InvalidArgumentException("Attempting to overwrite $travel link for {$zone->code()}");
    }

    $this->links[$travel] = $zone;
    $zone->links[$travel] = $this;
  }

  /**
   * Returns a short code to identify a map zone.  Relies on the fact that regions can always be
   * uniquely identified by the first three letters.  The string is generated by these letters
   * followed by the zone number, such as "aus3" for zone 3 in Australia.
   */
  public function code() {
    return strtolower(substr($this->region, 0, 3)) . $this->location_number;
  }

  //TODO: compute distance to another zone
  //public static function distance($
}