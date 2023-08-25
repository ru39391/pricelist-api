<?php

namespace Zoomx\Controllers\Api\Common;

trait CommonTrait
{
  private function isNumValid($value)
  {
    return !empty($value) && is_numeric($value) && $value >= 0;
  }

  private function isStrValid($value)
  {
    return !empty($value) && is_string($value) && mb_strlen($value) < 255;
  }

  public function getItem($data)
  {
    return zoomx('modx')->getObject(\pricelistDept::class, [
      'item_id' => $data['item_id']
    ]);
  }

  public function getData()
  {
    return json_decode(file_get_contents('php://input'), true);
  }

  public function validateData($item, $dateKey)
  {
    if (is_array($item) && count($item) > 0) {
      $validatedData = [];

      foreach ($item as $key => $value) {
        switch ($key) {
          case 'name':
            $validatedData[$key] = [
              $key => $this->isStrValid($value) ? trim($value) : $value,
              'isValid' => $this->isStrValid($value)
            ];
            break;
          default:
            $validatedData[$key] = [
              $key => $this->isNumValid($value) ? (int)$value : $value,
              'isValid' => $this->isNumValid($value)
            ];
            break;
        }
      }

      $validatedData[$dateKey] = in_array(false, array_map(fn($item) => $item['isValid'], $validatedData), true) ? null : date('Y-m-d H:i:s');
      return array_merge(...array_map(fn($key, $value) => [$key => is_array($value) ?  $value[$key] : $value], array_keys($validatedData), $validatedData));
    }
    return null;
  }
}
