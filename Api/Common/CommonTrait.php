<?php

namespace Zoomx\Controllers\Api\Common;

use Zoomx\Controllers\Constants;

trait CommonTrait
{
  private function isNumeric($value)
  {
    return is_numeric($value);
  }

  private function isBoolValid($value)
  {
    return $this->isNumeric($value) && ($value === 0 || $value === 1);
  }

  private function isNumValid($value)
  {
    return $this->isNumeric($value) && $value >= 0;
  }

  private function isStrValid($value)
  {
    return !empty($value) && is_string($value) && mb_strlen($value) < 255;
  }

  private function isCategoryIdValid($value)
  {
    return !empty($value) ? $this->isNumValid($value) : true;
  }

  private function handleEmptyVal($value)
  {
    return empty($value) ? $value : (int)$value;
  }

  private function handleArr($arr) {
    return array_map(fn($item) => $item->toArray(), $arr);
  }

  private function validateKeys($arr, $keys)
  {
    $validatedArr = array_map(fn($item) => in_array($item, array_keys($arr), true), $keys);
    return array_reduce($validatedArr, fn($carry, $item) => $carry && $item, true);
  }

  public function getItem($class, $data)
  {
    return zoomx('modx')->getObject($class, [
      Constants::ID_KEY => $data[Constants::ID_KEY]
    ]);
  }

  public function getChildren($children, $dataKey, $value)
  {
    return array_map(fn($item) => $this->handleArr(zoomx('modx')->getCollection($item, [$dataKey => $value])), $children);
  }

  public function getInputData()
  {
    return json_decode(file_get_contents('php://input'), true);
  }

  public function validateData($item, $dateKey, $keys)
  {
    if (is_array($item) && count($item) > 0 && $this->validateKeys($item, $keys)) {
      $validatedData = [];
      foreach ($item as $key => $value) {
        switch ($key) {
          case Constants::NAME_KEY:
            $validatedData[$key] = [
              $key => $this->isStrValid($value) ? trim($value) : $value,
              Constants::IS_VALID_KEY => $this->isStrValid($value)
            ];
            break;
          case Constants::COMPLEX_KEY:
            $validatedData[$key] = [
              $key => $this->isStrValid($value) ? trim($value) : $value,
              Constants::IS_VALID_KEY => $this->isStrValid($value)
            ];
            break;
          case Constants::IS_COMPLEX_KEY:
            $validatedData[$key] = [
              $key => $this->isBoolValid($value) ? (int)$value : $value,
              Constants::IS_VALID_KEY => $this->isBoolValid($value)
            ];
            break;
          case Constants::IS_COMPLEX_ITEM_KEY:
            $validatedData[$key] = [
              $key => $this->isBoolValid($value) ? (int)$value : $value,
              Constants::IS_VALID_KEY => $this->isBoolValid($value)
            ];
            break;
          case Constants::IS_VISIBLE_KEY:
            $validatedData[$key] = [
              $key => $this->isBoolValid($value) ? (int)$value : $value,
              Constants::IS_VALID_KEY => $this->isBoolValid($value)
            ];
            break;
          case Constants::GROUP_KEY:
            $validatedData[$key] = [
              $key => $this->isCategoryIdValid($value) ? $this->handleEmptyVal($value) : $value,
              Constants::IS_VALID_KEY => $this->isCategoryIdValid($value)
            ];
            break;
          default:
            $validatedData[$key] = [
              $key => $this->isNumValid($value) ? (int)$value : $value,
              Constants::IS_VALID_KEY => $this->isNumValid($value)
            ];
            break;
        }
      }

      $validatedData[$dateKey] = in_array(false, array_map(fn($item) => $item[Constants::IS_VALID_KEY], $validatedData), true) ? null : date('Y-m-d H:i:s');
      return $validatedData;
      //return array_merge(...array_map(fn($key, $value) => [$key => is_array($value) ?  $value[$key] : $value], array_keys($validatedData), $validatedData));
    }
    return null;
  }
}
