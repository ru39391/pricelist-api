<?php
/** @var FastRoute\RouteCollector  $router */
/** @var modX  $modx */

$router->get('services', Zoomx\Controllers\ServicesController::class);
$router->get('api/res', Zoomx\Controllers\Api\Res\GetController::class);

$routes = [
  'pricelist' => 'Pricelist',
  'depts' => 'Department',
  'subdepts' => 'Subdepartment',
  'groups' => 'Group'
];

$methods = [
  'get' => 'GetController',
  'post' => 'CreateController',
  'patch' => 'UpdateController',
  'delete' => 'DeleteController'
];

foreach ($routes as $key => $value) {
  foreach ($methods as $method => $controller) {
    $router->{$method}("api/{$key}", "Zoomx\\Controllers\\Api\\$value\\$controller");
  }
}
