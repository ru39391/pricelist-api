<?php

namespace Zoomx\Controllers\Api\Subdepartment;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  public function index()
  {
    $subdeptsColl = $this->modx->getCollection(\PricelistSpecialization::class, array('name:!=' => ' '));
    if (count($subdeptsColl) > 0) {
      $subdepts = array_map(fn($item) => array(
        'id' => $item->id,
        'name' => trim($item->name),
        'dept' => $item->department_id,
        'other_id' => $item->other_id,
        'page_id' => $item->page_id,
        'sort' => $item->sort,
      ), $subdeptsColl);

      return jsonx($subdepts);
    }

    return jsonx([], [], 404);
  }
}
