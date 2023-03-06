<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  private function getSubdept($arr, $parent) {
    return array_filter($arr, fn($item) => $item['department_id'] === $parent);
  }

  public function index()
  {
    $plDepts = $this->modx->getCollection(\PricelistDepartment::class);
    $plSubdepts = $this->modx->getCollection(\PricelistSpecialization::class);
    $plGroups = $this->modx->getCollection(\PricelistGroupOfServices::class);
    if (count($plDepts) > 0) {
      $subdepts = array();
      foreach ($plSubdepts as $item) {
        array_push($subdepts, array(
          'id' => $item->id,
          'department_id' => $item->department_id,
        ));
      };

      $groups = array();
      foreach ($plGroups as $item) {
        array_push($groups, array(
          'id' => $item->id,
          'department_id' => $item->department_id,
        ));
      };

      $depts = array();
      foreach ($plDepts as $item) {
        array_push($depts, array(
          'id' => $item->id,
          'name' => $item->name,
          'subdepts' => array_map(fn($arr) => $arr['id'], $this->getSubdept($subdepts, $item->id)),
          'groups' => array_map(fn($arr) => $arr['id'], $this->getSubdept($groups, $item->id)),
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
