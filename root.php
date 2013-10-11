<?php
/**
 * Includes all necessary files, initializes data, sets up global objects and functions, etc
 */

/**
 * Given a class name, pulls in the file
 */
function require_class($name) {
  require_once(ROOT . "/classes/$name.php");
}

// Include config as all parts of the app need it
require_once("config.inc.php");
require_once(ROOT . '/vendor/autoload.php');
require_class("Map");

// Set up a global database connection to simplify other scripts
$DB = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
if ($DB->connect_error) {
  die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

Twig_Autoloader::register();

// Set up map data
Map::initialize();
