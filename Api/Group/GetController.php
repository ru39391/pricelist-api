<?php

namespace Zoomx\Controllers\Api\Group;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  public function index()
  {
    $plGroups = $this->modx->getCollection(\PricelistGroupOfServices::class);
    if (count($plGroups) > 0) {
      $groups = array();
      foreach ($plGroups as $item) {
        array_push($groups, array(
          'id' => $item->id,
          'name' => $item->name,
          'department_id' => $item->department_id,
          'specialization_id' => $item->specialization_id,
          'other_id' => $item->other_id,
          'page_id' => $item->page_id,
          'sort' => $item->sort,
        ));
      };

      return jsonx($groups);
    }

    return jsonx([], [], 404);
  }
}
