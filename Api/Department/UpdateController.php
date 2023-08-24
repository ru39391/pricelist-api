<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Department\BaseDeptController;
use Zoomx\Controllers\Api\Department\DeptsTrait;

class UpdateController extends BaseDeptController
{
  use DeptsTrait;

  private function updateItem($data)
  {
    $response = $this->getItem('item_id');

    if ((bool)$response) {
      $response->set('name', $data['name']);
      $response->save();
      zoomx('modx')->cacheManager->clearCache();

      return $response->toArray();
    }
    return null;
  }

  public function handleItem($data)
  {
    return $this->updateItem($data);
  }

  public function index()
  {
    return $this->handleData(['error_values' => Constants::VALUES_UPDATE_ERROR_MSG]);
  }
}
