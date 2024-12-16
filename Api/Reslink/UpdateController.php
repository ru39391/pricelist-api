<?php

namespace Zoomx\Controllers\Api\Reslink;

use Zoomx\Controllers\Api\Common\UpdateController as CommonUpdateController;

class UpdateController extends CommonUpdateController
{
  public function index()
  {
    return $this->updateData(\pricelistLink::class);
  }
}
