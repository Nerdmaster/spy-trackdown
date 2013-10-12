<?php

abstract class BaseController {
  /** Twig loader */
  protected $loader;

  /** Twig instance */
  protected $twig;

  /** Array of path elements (after the controller) */
  protected $path_array;

  public function __construct($path_array) {
    $this->loader = new Twig_Loader_Filesystem(ROOT . '/templates');

    $env_config = array();
    if (PRODUCTION) {
      $env_config["cache"] = ROOT . '/template-cache';
    }
    $this->twig = new Twig_Environment($this->loader, $env_config);
    $this->path_array = $path_array;
  }

  /**
   * Renders the template passed in with the given variables
   */
  public function render_template($template, $variables = array()) {
    $template = $this->twig->loadTemplate($template);

    // Default variables - passed-in $variables can override these
    $defaults = array(
      "WEBROOT" => WEBROOT,
      "APPNAME" => APPNAME
    );

    echo $template->render(array_merge($defaults, $variables));
  }

  abstract public function render();
}
