<?php

namespace Zoomx\Controllers\Api\Subdepartment;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  private function getSubdept($arr, $parent) {
    return array_filter($arr, fn($item) => $item['specialization_id'] === $parent);
  }

  public function index()
  {
    $plSubdepts = $this->modx->getCollection(\PricelistSpecialization::class);
    $plGroups = $this->modx->getCollection(\PricelistGroupOfServices::class);
    if (count($plSubdepts) > 0) {
      $groups = array();
      foreach ($plGroups as $item) {
        array_push($groups, array(
          'id' => $item->id,
          'specialization_id' => $item->specialization_id,
        ));
      };

      $subdepts = array();
      foreach ($plSubdepts as $item) {
        array_push($subdepts, array(
          'id' => $item->id,
          'name' => $item->name,
          'department_id' => $item->department_id,
          'groups' => array_map(fn($arr) => $arr['id'], $this->getSubdept($groups, $item->id)),
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
