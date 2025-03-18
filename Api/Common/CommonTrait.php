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

  private function isStrValid($value, $class)
  {
    $isValueValid = !empty($value) && is_string($value);

    return $class === \pricelistLink::class ? $isValueValid : $isValueValid && mb_strlen($value) <= 255;
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
    $validatedKeys = array_merge(...array_map(fn($key) => array($key => array_key_exists($key, $arr)), $keys));
    $validatedValues = array_map(fn($key) => $validatedKeys[$key], $keys);

    return [
      'validatedKeys' => $validatedKeys,
      'isKeysValid' => array_reduce($validatedValues, fn($carry, $item) => $carry && $item, true)
    ];
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

  public function validateData($item, $dateKey, $keys, $class)
  {
    ['validatedKeys' => $validatedKeys, 'isKeysValid' => $isKeysValid] = $this->validateKeys($item, $keys);
    $isValid = is_array($item) && count($item) > 0 && $isKeysValid;

    $strKeys = [
      Constants::NAME_KEY,
      Constants::COMPLEX_KEY,
      Constants::DEPTS_PARAM_KEY,
      Constants::SUBDEPTS_PARAM_KEY,
      Constants::GROUPS_PARAM_KEY,
      Constants::ITEMS_PARAM_KEY,
      Constants::CONFIG_KEY
    ];
    $boolKeys = [Constants::IS_COMPLEX_KEY, Constants::IS_COMPLEX_ITEM_KEY, Constants::IS_VISIBLE_KEY];
    $categoryKeys = [Constants::GROUP_KEY];

    if ($isValid) {
      $validatedData = [];
      $data = array_merge(...array_map(fn($key) => array($key => $item[$key]), array_keys(count($keys) > 0 ? $validatedKeys : $item)));

      foreach ($data as $key => $value) {
        if (in_array($key, $strKeys)) {
          $validatedData[$key] = [
            $key => $this->isStrValid($value, $class) ? trim($value) : $value,
            Constants::IS_VALID_KEY => $this->isStrValid($value, $class)
          ];
        } elseif (in_array($key, $boolKeys)) {
          $validatedData[$key] = [
            $key => $this->isBoolValid($value) ? (int)$value : $value,
            Constants::IS_VALID_KEY => $this->isBoolValid($value)
          ];
        } elseif (in_array($key, $categoryKeys)) {
          $validatedData[$key] = [
            $key => $this->isCategoryIdValid($value) ? $this->handleEmptyVal($value) : $value,
            Constants::IS_VALID_KEY => $this->isCategoryIdValid($value)
          ];
        } else {
          $validatedData[$key] = [
            $key => $this->isNumValid($value) ? (int)$value : $value,
            Constants::IS_VALID_KEY => $this->isNumValid($value)
          ];
        }
      }

      $isDataNotValid = in_array(false, array_map(fn($item) => $item[Constants::IS_VALID_KEY], $validatedData), true);
      $validatedData[Constants::IS_VALID_KEY] = $isDataNotValid ? false : true;
      $validatedData[$dateKey] = $isDataNotValid ? null : date('Y-m-d H:i:s');

      return array_merge(...array_map(fn($key, $value) => [$key => is_array($value) ?  $value[$key] : $value], array_keys($validatedData), $validatedData));
    }
    return [
      Constants::ID_KEY => $item[Constants::ID_KEY],
      Constants::NAME_KEY => $item[Constants::NAME_KEY],
      Constants::IS_VALID_KEY => $isValid,
      'isArray' => is_array($item),
      'isNotEmpty' => count($item) > 0,
      'isValidKeys' => $isKeysValid,
      'validatedKeys' => $validatedKeys
    ];
  }
}
