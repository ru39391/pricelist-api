<?php

namespace Zoomx\Controllers\Api\Subdepartment;

use Zoomx\Controllers\Api\Common\UpdateController as CommonUpdateController;

class UpdateController extends CommonUpdateController
{
  public function index()
  {
    return $this->updateData(\pricelistSubdept::class);
  }
}
