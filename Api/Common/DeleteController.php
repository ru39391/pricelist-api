<?php

namespace Zoomx\Controllers\Api\Common;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CommonController;
use Zoomx\Controllers\Api\Common\CommonTrait;

class DeleteController extends CommonController
{
  use CommonTrait;

  private function deleteItem($data, $dateKey, $class)
  {
    $output = [];
    $item = $this->getItem($class, $data);
    if ((bool)$item) {
      $output = $item->remove() ? $data : $item->toArray();
      zoomx('modx')->cacheManager->clearCache();
    } else {
      $output = $data;
      $output[$dateKey] = $item;
    }

    return $output;
  }

  public function handleItem($data, $dateKey, $class)
  {
    return $this->deleteItem($data, $dateKey, $class);
  }

  protected function deleteData($class)
  {
    return $this->handleData(
      $class,
      Constants::UPDATEDON_KEY,
      Constants::VALUES_DELETE_ERROR_MSG
    );
  }
}
