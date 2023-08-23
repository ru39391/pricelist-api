<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Api\AuthController;

class GetController extends AuthController
{
  private function getChildren($objClass, $department_id) {
    $arr = $this->modx->getCollection($objClass, array('department_id' => $department_id));
    return array_map(fn($item) => array(
      'id' => $item->id,
      'name' => trim($item->name),
    ), $arr);
  }

  public function index()
  {
    $deptsColl = $this->modx->getCollection(\pricelistDept::class, array('name:!=' => ''));
    if (count($deptsColl) > 0) {
      $depts = array_map(fn($item) => array(
        'id' => $item->id,
        'item_id' => $item->item_id,
        'name' => trim($item->name),
        //'subdepts' => $this->getChildren(\PricelistSpecialization::class, $item->id),
        //'groups' => $this->getChildren(\PricelistGroupOfServices::class, $item->id),
        //'other_id' => $item->other_id,
        //'page_id' => $item->page_id,
        //'sort' => $item->sort,
      ), $deptsColl);

      return jsonx($depts);
    }

    return jsonx([], [], 400);
  }
}
