<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Department\BaseDeptController;

class CreateController extends BaseDeptController
{
  private function createItem($data)
  {
    $response = zoomx('modx')->newObject(\pricelistDept::class, $data);
    $response->save();
    zoomx('modx')->cacheManager->clearCache();

    return $response->toArray();
  }

  public function handleItem($data)
  {
    return $this->createItem($data);
  }

  public function index()
  {
    return $this->handleData(
      'createdon',
      [Constants::VALUES_ERROR_KEY => Constants::VALUES_ERROR_MSG]
    );
  }
}
