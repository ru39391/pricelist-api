<?php

namespace Zoomx\Controllers\Api\Subdepartment;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  public function index()
  {
    $plSubdepts = $this->modx->getCollection(\PricelistSpecialization::class);
    if (count($plSubdepts) > 0) {
      $subdepts = array();
      foreach ($plSubdepts as $item) {
        array_push($subdepts, array(
          'id' => $item->id,
          'name' => $item->name,
          'department_id' => $item->department_id,
          'other_id' => $item->other_id,
          'page_id' => $item->page_id,
          'sort' => $item->sort,
        ));
      };

      return jsonx($subdepts);
    }

    return jsonx([], [], 404);
  }
}
