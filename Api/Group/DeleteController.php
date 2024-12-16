<?php

namespace Zoomx\Controllers\Api\Group;

use Zoomx\Controllers\Api\Common\DeleteController as CommonDeleteController;

class DeleteController extends CommonDeleteController
{
  public function index()
  {
    return $this->deleteData(\pricelistGroup::class);
  }
}
