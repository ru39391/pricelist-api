<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\AuthController;

class CreateController extends AuthController
{
  private function validateData($value)
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

  private function createDept($data)
  {
    $response = zoomx('modx')->newObject(\pricelistDept::class, $data);
    $response->save();
    zoomx('modx')->cacheManager->clearCache();

    return $response->toArray();
  }

  public function index()
  {
    $response = [];
    $data = json_decode(file_get_contents('php://input'), true);
    $validatedData = array_map(fn($item) => $this->validateData($item), $data);

    if (in_array(null, $validatedData, true)) {
      foreach ([
        'success' => false,
        'message' => Constants::DATA_ERROR_MSG,
      ] as $key => $value) {
        $response[$key] = $value;
      }
    } else {
      foreach ($validatedData as $item) {
        if (in_array(null, $item, true)) {
          $response[] = null;
        } else {
          $response[] = $this->createDept([
            'item_id' => $item['item_id'],
            'name' => $item['name'],
          ]);
        }
      }

      $nulledItems = array_filter(array_values($response), fn($item) => $item === null);
      if (count($nulledItems) === count($validatedData)) {
        foreach ([
          'success' => false,
          'message' => Constants::VALUES_ERROR_MSG,
        ] as $key => $value) {
          $response[$key] = $value;
        }
      } else {
        $response['success'] = true;
      }
    }

    $responseCode = $response['success'] ? 201 : 400;

    return jsonx($response, [], $responseCode);
  }
}
