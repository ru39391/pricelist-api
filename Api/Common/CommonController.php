<?php

namespace Zoomx\Controllers\Api\Common;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\AuthController;
use Zoomx\Controllers\Api\Common\CommonTrait;

class CommonController extends AuthController
{
  use CommonTrait;

  private function batchItems($arr, $dateKey, $class)
  {
    $output = [];

    foreach ($arr as $item) {
      $output[] = $item[$dateKey] ? $this->handleItem($item, $dateKey, $class) : $item;
    }

    return $output;
  }

  protected function getItems($class, $children = [], $dataKey = '')
  {
    $response = [];
    $items = zoomx('modx')->getCollection($class);

    foreach($items as $item) {
      $data = $item->toArray();
      $response[] = $data;
    }

    $responseCode = 200;

    return jsonx($response, [], $responseCode);
  }

  protected function handleData($class, $dateKey, $error, $keys = [])
  {
    $response = [];
    $validatedData = array_map(fn($item) => $this->validateData($item, $dateKey, $keys), $this->getInputData());

    if (in_array(null, $validatedData, true)) {
      foreach ([
        'success' => false,
        'message' => Constants::DATA_ERROR_MSG,
      ] as $key => $value) {
        $response[$key] = $value;
      }
    } else {
      for ($i = 0; $i < count($validatedData); $i += 100) {
        $currData = array_slice($validatedData, $i, 100);
        $response = $this->batchItems($currData, $dateKey, $class);
      }

      $handledItems = array_filter($response, fn($item) => is_array($item));
      $itemDates = array_map(fn($item) => $item[$dateKey], $handledItems);
      $nulledItems = array_filter($itemDates, fn($item) => $item === null);
      if (count($nulledItems) === count($validatedData)) {
        foreach ([
          'success' => false,
          'message' => $error,
        ] as $key => $value) {
          $response[$key] = $value;
        }
      } else {
        $response['success'] = true;
      }
    }

    $responseCode = $response['success'] ? 200 : 400;

    return jsonx($response, [], $responseCode);
    //return jsonx($validatedData, [], 200);
  }
}
