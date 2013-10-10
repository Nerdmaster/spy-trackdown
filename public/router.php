<?php

require_once(__DIR__ . "/../root.php");

// Parse the URL
$path = array_key_exists("url", $_GET) ? $_GET["url"] : "/";

// Whitelist of "controllers" (this will be a VERY loose MVC kind of thing)
$controllers = array("home");

// Get the path elements and specifically the controller name
$path_elements = explode("/", $path);
$controller_name = array_shift($path_elements);

if (empty($path) || $path == "/") {
  $controller_name = "home";
}

if (in_array($controller_name, $controllers)) {
  $class = ucfirst($controller_name) . "Controller";
  require_class("controllers/$class");
  $controller = new $class($path_elements);
  print $controller->render();
}

header('HTTP/1.1 404 Not Found');
echo "The resource you requested does not exist.  Please double-check your URL and try again.";
exit(0);

?>
