<?php

require_once(__DIR__ . "/../root.php");
echo APPNAME . "<br />\n";

// Parse the URL
$path = $_GET["url"];

if (empty($path) || $path == "/") {
  echo "Welcome!";
  exit;
}

echo "Unknown Request";

?>
