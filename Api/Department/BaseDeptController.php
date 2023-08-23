<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\AuthController;
use Zoomx\Controllers\Api\Department\DeptsTrait;

class BaseDeptController extends AuthController
{
  use DeptsTrait;

  protected function handleData($errors)
  {
    $response = [];
    $data = $this->getData();
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
          $response[] = $this->handleItem([
            'item_id' => $item['item_id'],
            'name' => $item['name'],
          ]);
        }
      }

      $nulledItems = array_filter(array_values($response), fn($item) => $item === null);
      if (count($nulledItems) === count($validatedData)) {
        foreach ([
          'success' => false,
          'message' => $errors['error_values'],
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
