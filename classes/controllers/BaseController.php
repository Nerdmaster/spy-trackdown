<?php

abstract class BaseController {
  /** Twig loader */
  protected $loader;

  /** Twig instance */
  protected $twig;

  /** Array of path elements (after the controller) */
  protected $path_array;

  /** Template to render - not set on errors */
  protected $template;

  /**
   * Local variables - controller sets these via $this->variable("name", "value").  Defaults are
   * merged in (not overwriting controller-set variables) and the resulting data is passed on to
   * whatever twig template is rendered.
   */
  private $variables;

  /**
   * Error status to print out, such as "HTTP/1.1 404 Not Found".  Setting this causes template
   * processing and output to be skipped.
   *
   * TODO: let subclass just throw an exception and use a lookup to produce message -
   * e.g., MissingResource would cause a 404, AccessDenied 403, unknown exceptions 500, etc
   *
   * TODO: Allow status without text to just render the template for contextual messages (i.e.,
   * use $text or something to allow rendering custom text within layout, and just spit out the
   * appropriate headers based on exceptions as mentioned above)
   */
  protected $http_status;

  /** Currently just for error text output.  FIXME!!  */
  protected $text;

  public function __construct($path_array) {
    $this->loader = new Twig_Loader_Filesystem(ROOT . '/templates');

    $env_config = array();
    if (PRODUCTION) {
      $env_config["cache"] = ROOT . '/.template-cache';
    }
    $this->twig = new Twig_Environment($this->loader, $env_config);
    $this->path_array = $path_array;
    $this->variables = array();
  }

  /**
   * Dispatches to subclass process() method and then determines what to render
   */
  public function render() {
    $this->process();

    if ($this->http_status) {
      // TODO: Make this render within the layout
      header($this->http_status);
      print $this->text;
      exit(1);
    }

    $this->render_template();
  }

  /**
   * Renders the template passed in with the given variables
   */
  public function render_template() {
    Twig_Autoloader::register();

    if (!$this->template) {
      return;
    }

    $template = $this->twig->loadTemplate($this->template . ".html.twig");

    // Default variables - manually-set variables can override these
    $defaults = array(
      "WEBROOT" => WEBROOT,
      "APPNAME" => APPNAME,
      "errors" => array(),
    );

    print $template->render(array_merge($defaults, $this->variables));
  }

  public function redirect_to($path) {
    $full_url = WEBROOT . $path;
    $this->text = "Redirecting you to $full_url";
    $this->http_status = "Location: $full_url";
  }

  /**
   * Sets a variable for the template to use
   */
  public function variable($name, $value) {
    $this->variables[$name] = $value;
  }

  /**
   * This is where the controller needs to set up its template or errors
   */
  abstract public function process();
}
