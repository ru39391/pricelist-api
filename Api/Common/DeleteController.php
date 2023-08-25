<?php

namespace Zoomx\Controllers\Api\Common;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CommonController;
use Zoomx\Controllers\Api\Common\CommonTrait;

class DeleteController extends CommonController
{
  use CommonTrait;

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

  protected function deleteData()
  {
    return $this->handleData(
      'updatedon',
      [Constants::VALUES_ERROR_KEY => Constants::VALUES_DELETE_ERROR_MSG]
    );
  }
}
