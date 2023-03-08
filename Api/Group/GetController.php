<?php

namespace Zoomx\Controllers\Api\Group;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  public function index()
  {
    $groupsColl = $this->modx->getCollection(\PricelistGroupOfServices::class);
    if (count($groupsColl) > 0) {
      $groups = array_map(fn($item) => array(
        'id' => $item->id,
        'name' => $item->name,
        'dept' => $item->department_id,
        'subdept' => $item->specialization_id,
        'other_id' => $item->other_id,
        'page_id' => $item->page_id,
        'sort' => $item->sort,
      ), $groupsColl);

      return jsonx($groups);
    }

    return jsonx([], [], 404);
  }
}
