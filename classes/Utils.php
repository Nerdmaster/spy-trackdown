<?php

/**
 * Pure static class for random crap that has no place in a proper OOP design
 */
class Utils {
  /** Generates a name for a game using text files in data/ */
  public static function generate_name() {
    $arr = file(ROOT . "/data/1");
    $adjective = rtrim($arr[array_rand($arr)]);

    $arr = file(ROOT . "/data/2");
    $flavor = rtrim($arr[array_rand($arr)]);

    $arr = file(ROOT . "/data/3");
    $animal = rtrim($arr[array_rand($arr)]);

    return sprintf("%s %s %s", $adjective, $flavor, $animal);
  }
}
