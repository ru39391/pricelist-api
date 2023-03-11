<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  private function getSubdepts($department_id) {
    $arr = $this->modx->getCollection(\PricelistSpecialization::class, array('department_id' => $department_id));
    return array_map(fn($item) => array(
      'id' => $item->id,
      'name' => trim($item->name),
    ), $arr);
  }

  public function index()
  {
    $deptsColl = $this->modx->getCollection(\PricelistDepartment::class, array('name:!=' => ''));
    if (count($deptsColl) > 0) {
      $depts = array_map(fn($item) => array(
        'id' => $item->id,
        'name' => trim($item->name),
        'subdepts' => $this->getSubdepts($item->id),
        'other_id' => $item->other_id,
        'page_id' => $item->page_id,
        'sort' => $item->sort,
      ), $deptsColl);

      return jsonx($depts);
    }

    return jsonx([], [], 404);
  }
}
