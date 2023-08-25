<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Department\BaseDeptController;
use Zoomx\Controllers\Api\Department\DeptsTrait;

class UpdateController extends BaseDeptController
{
  use DeptsTrait;

  private function updateItem($data, $dateKey)
  {
    $output = [];
    $response = $this->getItem($data);
    if ((bool)$response) {
      foreach (array_filter(array_keys($data), fn($key) => $key !== 'item_id') as $item) {
        $response->set($item, $data[$item]);
      }
      $response->save();
      zoomx('modx')->cacheManager->clearCache();
      $output = $response->toArray();
    } else {
      $output = $data;
      $output[$dateKey] = $response;
    }

    return $output;
  }

  public function handleItem($data, $dateKey)
  {
    return $this->updateItem($data, $dateKey);
  }

  public function index()
  {
    return $this->handleData(
      'updatedon',
      [Constants::VALUES_ERROR_KEY => Constants::VALUES_UPDATE_ERROR_MSG]
    );
  }
}
