<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Department\BaseDeptController;

class UpdateController extends BaseDeptController
{
  private function updateItem($data)
  {
    $response = zoomx('modx')->getObject(\pricelistDept::class, [
      'item_id' => $data['item_id']
    ]);

    if ($response !== null) {
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
