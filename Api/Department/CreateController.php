<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Department\BaseDeptController;
use Zoomx\Controllers\Api\Department\DeptsTrait;

class CreateController extends BaseDeptController
{
  use DeptsTrait;

  private function createItem($data, $dateKey)
  {
    $output = [];
    $item = $this->getItem($data);
    if((bool)$item) {
      $output = $item->toArray();
      $output[$dateKey] = null;
    } else {
      $response = zoomx('modx')->newObject(\pricelistDept::class, $data);
      $response->save();
      zoomx('modx')->cacheManager->clearCache();
      $output = $response->toArray();
    }

    return $output;
  }

  public function handleItem($data, $dateKey)
  {
    return $this->createItem($data, $dateKey);
  }

  public function index()
  {
    return $this->handleData(
      'createdon',
      [Constants::VALUES_ERROR_KEY => Constants::VALUES_CREATE_ERROR_MSG]
    );
  }
}
