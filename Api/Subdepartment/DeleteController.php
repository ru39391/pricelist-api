<?php

namespace Zoomx\Controllers\Api\Subdepartment;

use Zoomx\Controllers\Api\Common\DeleteController as CommonDeleteController;

class DeleteController extends CommonDeleteController
{
  public function index()
  {
    return $this->deleteData(\pricelistSubdept::class);
  }
}
