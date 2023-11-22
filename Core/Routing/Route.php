<?php

namespace Core\Routing;

/**
 * Route class
 */
class Route
{
  protected mixed $controller;
  protected string $method;
  protected int $accessLevel = 0;

  /**
   * Constructor
   *
   * @param mixed $controller Controller class
   * @param int $accessLevel = 0 Access level required to access the route (0 = public, 1 = user, 2 = admin)
   */
  public function __construct(mixed $controller, int $accessLevel = 0, string $method = 'GET')
  {
    $this->controller = $controller;
    $this->accessLevel = $accessLevel;
    $this->method = $method;
  }

  /**
   * Get the controller
   *
   * @return mixed
   */
  public function getController(): mixed
  {
    return $this->controller;
  }

  /**
   * Get the access level
   *
   * @return int
   */
  public function getAccessLevel(): int
  {
    return $this->accessLevel;
  }

  /**
   * Get the method
   *
   * @return string
   */
  public function getMethod(): string
  {
    return $this->method;
  }
}