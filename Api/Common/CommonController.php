<?php

namespace Zoomx\Controllers\Api\Common;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\AuthController;
use Zoomx\Controllers\Api\Common\CommonTrait;

class CommonController extends AuthController
{
  use CommonTrait;

  protected function getItems($class, $children = [], $dataKey = '')
  {
    $response = [];
    $items = zoomx('modx')->getCollection($class);
    foreach($items as $item) {
      $data = $item->toArray();
      if(count($children) > 0) {
        foreach($this->getChildren($children, $dataKey, $item->item_id) as $key => $value) {
          $data[$key] = $value;
        }
      }

      $response[] = $data;
    }

    $responseCode = count($items) > 0 ? 200 : 400;

    return jsonx($response, [], $responseCode);
  }

  protected function handleData($class, $dateKey, $errors)
  {
    $response = [];
    $validatedData = array_map(fn($item) => $this->validateData($item, $dateKey), $this->getInputData());

    if (in_array(null, $validatedData, true)) {
      foreach ([
        'success' => false,
        'message' => Constants::DATA_ERROR_MSG,
      ] as $key => $value) {
        $response[$key] = $value;
      }
    } else {
      foreach ($validatedData as $item) {
        $response[] = $item[$dateKey] ? $this->handleItem($item, $dateKey, $class) : $item;
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

    $responseCode = $response['success'] ? 200 : 400;

    return jsonx($response, [], $responseCode);
  }
}
