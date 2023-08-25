<?php
/** @var FastRoute\RouteCollector  $router */
/** @var modX  $modx */

$router->get('services', Zoomx\Controllers\ServicesController::class);
$router->get('api/pages', Zoomx\Controllers\Api\Page\GetController::class);

$routes = [
  'pricelist' => 'Pricelist',
  'depts' => 'Department',
  'subdepts' => 'Subdepartment',
  'groups' => 'Group'
];

foreach($routes as $key => $value) {
  $router->get("api/{$key}", "Zoomx\\Controllers\\Api\\$value\\GetController");
}

foreach($routes as $key => $value) {
  $router->post("api/{$key}", "Zoomx\\Controllers\\Api\\$value\\CreateController");
}

foreach($routes as $key => $value) {
  $router->put("api/{$key}", "Zoomx\\Controllers\\Api\\$value\\UpdateController");
}

foreach($routes as $key => $value) {
  $router->delete("api/{$key}", "Zoomx\\Controllers\\Api\\$value\\DeleteController");
}
