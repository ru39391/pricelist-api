<?php

namespace Zoomx\Controllers\Api\Pricelist;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  public function index()
  {
    $plColl = $this->modx->getCollection(\PricelistService::class, array('name:!=' => ' '));
    if (count($plColl) > 0) {
      $pl = array_map(fn($item) => array(
        'id' => $item->id,
        'name' => $item->name,
        'price' => $item->price,
        'dept' => $item->department_id,
        'subdept' => $item->specialization_id,
        'group' => $item->group_of_services_id,
        'other_id' => $item->other_id,
        'service_show' => (bool)(int)$item->service_show,
        'sort' => $item->sort,
      ), $plColl);

      return jsonx($pl);
    }

    return jsonx([], [], 404);
  }
}
