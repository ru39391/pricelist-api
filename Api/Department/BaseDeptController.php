<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\AuthController;
use Zoomx\Controllers\Api\Department\DeptsTrait;

class BaseDeptController extends AuthController
{
  use DeptsTrait;

  protected function handleData($dateKey, $keys, $errors)
  {
    $response = [];
    $validatedData = array_map(fn($item) => $this->validateData($item), $this->getData());

    if (in_array(null, $validatedData, true)) {
      foreach ([
        'success' => false,
        'message' => Constants::DATA_ERROR_MSG,
      ] as $key => $value) {
        $response[$key] = $value;
      }
    } else {
      foreach ($validatedData as $item) {
        $handledData = array_combine($keys, array_map(fn($key) => $item[$key], $keys));
        $handledData[$dateKey] = $item[Constants::BOOL_VALID_KEY] ? date('Y-m-d H:i:s') : null;
        $response[] = $item[Constants::BOOL_VALID_KEY] ? $this->handleItem($handledData) : $handledData;
      }

      $handledItems = array_filter($response, fn($item) => is_array($item));
      $itemDates = array_map(fn($item) => $item[$dateKey], $handledItems);
      $nulledItems = array_filter($itemDates, fn($item) => $item === null);
      if (count($nulledItems) === count($validatedData)) {
        foreach ([
          'success' => false,
          'message' => $errors[Constants::VALUES_ERROR_KEY],
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
