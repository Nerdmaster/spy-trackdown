<?php

require_once(__DIR__ . "/../root.php");
echo APPNAME . "<br />\n";

// Parse the URL
$path = array_key_exists("url", $_GET) ? $_GET["url"] : "/";

if (empty($path) || $path == "/") {
  echo "Welcome!";
  exit;
}

echo "Unknown Request";

?>
