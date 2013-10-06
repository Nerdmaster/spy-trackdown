<?php

require_once(__DIR__ . "/../config.inc.php");

list($script, $host, $rootname, $rootpw) = $argv;

if (!$host || !$rootname || !$rootpw) {
  print "Usage: `php $script host root-username root-password`\n";
  print "    Creates the initial database needed to run the Spy Tracker web app\n";
  print "    Uses data in config.inc.php to determine new database, user, and password.\n";
  exit;
}

$db = new mysqli($host, $rootname, $rootpw);
$db->query(sprintf("CREATE DATABASE %s", DBNAME));
$db->query(sprintf("GRANT ALL ON %s.* TO '%s'@'%s' IDENTIFIED BY '%s';", DBNAME, DBUSER, DBHOST, DBPASS));
