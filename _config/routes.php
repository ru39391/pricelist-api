<?php
/** @var FastRoute\RouteCollector  $router */
/** @var modX  $modx */

$router->get('services', Zoomx\Controllers\ServicesController::class);
$router->get('api/depts', Zoomx\Controllers\Api\Department\GetController::class);
$router->get('api/subdepts', Zoomx\Controllers\Api\Subdepartment\GetController::class);
$router->get('api/svgroups', Zoomx\Controllers\Api\Group\GetController::class);
$router->get('api/pricelist', Zoomx\Controllers\Api\Pricelist\GetController::class);