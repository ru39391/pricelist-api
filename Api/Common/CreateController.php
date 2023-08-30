<?php

namespace Zoomx\Controllers\Api\Common;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CommonController;
use Zoomx\Controllers\Api\Common\CommonTrait;

class CreateController extends CommonController
{
  use CommonTrait;

  private function createItem($data, $dateKey, $class)
  {
    $output = [];
    $item = $this->getItem($class, $data);
    if((bool)$item) {
      $output = $item->toArray();
      $output[$dateKey] = null;
    } else {
      $response = zoomx('modx')->newObject($class, $data);
      $response->save();
      zoomx('modx')->cacheManager->clearCache();
      $output = $response->toArray();
    }

    return $output;
  }

  public function handleItem($data, $dateKey, $class)
  {
    return $this->createItem($data, $dateKey, $class);
  }

  protected function createData($class)
  {
    return $this->handleData(
      $class,
      Constants::CREATEDON_KEY,
      Constants::VALUES_CREATE_ERROR_MSG
    );
  }
}
