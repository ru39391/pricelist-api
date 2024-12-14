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

  protected function handleData($class, $dateKey, $error, $keys = [])
  {
    $result = [];
    $response = [];
    $validatedData = array_map(fn($item) => $this->validateData($item, $dateKey, $keys), $this->getInputData());
    $validItems = array_filter($validatedData, fn($item) => $item[Constants::IS_VALID_KEY] === true);
    $inValidItems = array_filter($validatedData, fn($item) => $item[Constants::IS_VALID_KEY] === false);
    $counter = [
      'valid' => count($validItems),
      'inValid' => count($inValidItems),
      'total' => count($validatedData)
    ];

    if (count($validatedData) === count($inValidItems)) {
      foreach ([
        'success' => false,
        'message' => Constants::DATA_ERROR_MSG,
        'counter' => $counter,
        'inValid' => $inValidItems,
      ] as $key => $value) {
        $response[$key] = $value;
      }
    } else {
      for ($i = 0; $i < count($validItems); $i += 100) {
        $currData = array_slice($validItems, $i, 100);
        $result[] = $this->batchItems($currData, $dateKey, $class);
      }

      $handledItems = array_filter(array_merge(...$result), fn($item) => is_array($item));
      $validHandledItems = array_filter($handledItems, fn($item) => $item[$dateKey] !== null);
      $inValidHandledItems = array_filter($handledItems, fn($item) => $item[$dateKey] === null);

      if (count($validItems) === count($inValidHandledItems)) {
        foreach ([
          'success' => false,
          'message' => $error,
        ] as $key => $value) {
          $response[$key] = $value;
        }
      } else {
        foreach ([
          'success' => true,
        ] as $key => $value) {
          $response[$key] = $value;
        }
      }

      foreach ([
        'counter' => array_merge(
          [
            'succeed' => count($validHandledItems),
            'failed' => count($inValidHandledItems),
          ],
          $counter
        ),
        'succeed' => $validHandledItems,
        'failed' => $inValidHandledItems,
        'inValid' => $inValidItems,
      ] as $key => $value) {
        $response[$key] = $value;
      }
    }

    $responseCode = $response['success'] ? 200 : 400;

    return jsonx($response, [], $responseCode);
  }
}
