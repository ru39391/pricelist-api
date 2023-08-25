<?php

namespace Zoomx\Controllers\Api\Common;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CommonController;
use Zoomx\Controllers\Api\Common\CommonTrait;

class UpdateController extends CommonController
{
  use CommonTrait;

  private function updateItem($data, $dateKey)
  {
    $output = [];
    $item = $this->getItem($data);
    if ((bool)$item) {
      foreach (array_filter(array_keys($data), fn($key) => $key !== 'item_id') as $key) {
        $item->set($key, $data[$key]);
      }
      $item->save();
      zoomx('modx')->cacheManager->clearCache();
      $output = $item->toArray();
    } else {
      $output = $data;
      $output[$dateKey] = $item;
    }

    return $output;
  }

  public function handleItem($data, $dateKey)
  {
    return $this->updateItem($data, $dateKey);
  }

  protected function updateData()
  {
    return $this->handleData(
      'updatedon',
      [Constants::VALUES_ERROR_KEY => Constants::VALUES_UPDATE_ERROR_MSG]
    );
  }
}
