<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  public function index()
  {
    $plDepts = $this->modx->getCollection(\PricelistDepartment::class);
    if (count($plDepts) > 0) {
      $depts = array();
      foreach ($plDepts as $item) {
        array_push($depts, array(
          'id' => $item->id,
          'name' => $item->name,
          'other_id' => $item->other_id,
          'page_id' => $item->page_id,
          'sort' => $item->sort,
        ));
      };

      return jsonx($depts);
    }

    return jsonx([], [], 404);
  }
}
