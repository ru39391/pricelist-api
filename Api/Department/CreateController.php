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
    $item = $this->getItem('item_id', $data['item_id']);
    if((bool)$item) {
      $response = $item->toArray();
      $response[$dateKey] = null;
    } else {
      $response = zoomx('modx')->newObject(\pricelistDept::class, $data);
      $response->save();
      zoomx('modx')->cacheManager->clearCache();
    }

    return (bool)$item ? $response : $response->toArray();
  }

  public function handleItem($data, $dateKey)
  {
    return $this->createItem($data, $dateKey);
  }

  public function index()
  {
    return $this->handleData(
      'createdon',
      [Constants::VALUES_ERROR_KEY => Constants::VALUES_ERROR_MSG]
    );
  }
}
