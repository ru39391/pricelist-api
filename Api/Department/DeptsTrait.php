<?php

namespace Zoomx\Controllers\Api\Department;

trait DeptsTrait
{
  public function getData()
  {
    return json_decode(file_get_contents('php://input'), true);
  }

  public function validateData($value)
  {
    if (is_array($value) && count($value) > 0) {
      list($item_id, $name) = [$value['item_id'], trim($value['name'])];
      return [
        'item_id' => (empty($item_id) || !is_numeric($item_id) || $item_id <= 0) ? null : (int)$item_id,
        'name' => (empty($name) || mb_strlen($name) > 255) ? null : $name,
      ];
    }
    return null;
  }
}
