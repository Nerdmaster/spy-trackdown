<?php

/**
 * Pure static class for random crap that has no place in a proper OOP design
 */
class Utils {
  /** Generates a name for a game using text files in data/name-gen */
  public static function generate_name() {
    $files = array();
    $dh = opendir(ROOT . "/data/name-gen");
    while (false !== ($file = readdir($dh))) {
      if (!preg_match("/^\d+$/", $file)) {
        continue;
      }
      $files[] = $file;
    }

    sort($files);

    $words = array();
    foreach ($files as $file) {
      $arr = file(ROOT . "/data/name-gen/" . $file);
      $words[] = rtrim($arr[array_rand($arr)]);
    }

    return preg_replace("/\s+/", " ", trim(implode(" ", $words)));
  }
}
