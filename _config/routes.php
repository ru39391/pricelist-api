<?php
/** @var FastRoute\RouteCollector  $router */
/** @var modX  $modx */

$router->get('prices', Zoomx\Controllers\PricesController::class);
$router->get('api/res', Zoomx\Controllers\Api\Res\GetController::class);

$routes = [
  'pricelist' => 'Pricelist',
  'depts' => 'Department',
  'subdepts' => 'Subdepartment',
  'groups' => 'Group',
  'reslinks' => 'Reslink'
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
