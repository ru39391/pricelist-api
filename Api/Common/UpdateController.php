<?php

namespace Zoomx\Controllers\Api\Common;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CommonController;
use Zoomx\Controllers\Api\Common\CommonTrait;

class UpdateController extends CommonController
{
  use CommonTrait;

  private function updateItem($data, $dateKey, $class)
  {
    $output = [];
    $item = $this->getItem($class, $data);
    if ((bool)$item) {
      foreach (array_filter(array_keys($data), fn($key) => $key !== Constants::ID_KEY) as $key) {
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

  public function handleItem($data, $dateKey, $class)
  {
    return $this->updateItem($data, $dateKey, $class);
  }

  protected function updateData($class)
  {
    return $this->handleData(
      $class,
      Constants::UPDATEDON_KEY,
      Constants::VALUES_UPDATE_ERROR_MSG
    );
  }
}
