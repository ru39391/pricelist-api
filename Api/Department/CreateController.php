<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Api\Common\CreateController as CommonCreateController;

class CreateController extends CommonCreateController
{
  public function index()
  {
    return $this->createData(\pricelistDept::class);
  }
}
