<?php

namespace Zoomx\Controllers\Api\Pricelist;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  public function index()
  {
    $plServices = $this->modx->getCollection(\PricelistService::class);
    if (count($plServices) > 0) {
      $services = array();
      foreach ($plServices as $item) {
        array_push($services, array(
          'id' => $item->id,
          'name' => $item->name,
          'price' => $item->price,
          'department_id' => $item->department_id,
          'specialization_id' => $item->specialization_id,
          'group_of_services_id' => $item->group_of_services_id,
          'other_id' => $item->other_id,
          'service_show' => $item->service_show,
          'sort' => $item->sort,
        ));
      };

      return jsonx($services);
    }

    return jsonx([], [], 404);
  }
}
