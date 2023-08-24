<?php

namespace Zoomx\Controllers\Api\Department;

trait DeptsTrait
{
  public function getData()
  {
    return json_decode(file_get_contents('php://input'), true);
  }

  public function validateData($item, $dateKey)
  {
    if (is_array($item) && count($item) > 0) {
      list($id, $name) = array_values($item);
      list($isIdValid, $idNameValid) = [(!empty($id) && is_numeric($id) && $id >= 0), (!empty($name) && is_string($name) && mb_strlen($name) < 255)];
      return [
        'item_id' => $isIdValid ? (int)$id : $id,
        'name' => $idNameValid ? trim($name) : $name,
        $dateKey => $isIdValid && $idNameValid ? date('Y-m-d H:i:s') : null
      ];
    }
    return null;
  }
}
