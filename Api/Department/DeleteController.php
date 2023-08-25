<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Department\BaseDeptController;
use Zoomx\Controllers\Api\Department\DeptsTrait;

class DeleteController extends BaseDeptController
{
  use DeptsTrait;

  private function deleteItem($data, $dateKey)
  {
    $output = [];
    $item = $this->getItem($data);
    if ((bool)$item) {
      $output = $item->remove() ? $data : $item->toArray();
      zoomx('modx')->cacheManager->clearCache();
    } else {
      $output = $data;
      $output[$dateKey] = $item;
    }

    return $output;
  }

  public function handleItem($data, $dateKey)
  {
    return $this->deleteItem($data, $dateKey);
  }

  public function index()
  {
    return $this->handleData(
      'updatedon',
      [Constants::VALUES_ERROR_KEY => Constants::VALUES_DELETE_ERROR_MSG]
    );
  }
}
