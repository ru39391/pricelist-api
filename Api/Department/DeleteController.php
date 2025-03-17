<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\DeleteController as CommonDeleteController;

class DeleteController extends CommonDeleteController
{
  public function index()
  {
    return $this->deleteData(
      \pricelistDept::class,
      Constants::DEPT_KEYS
    );
  }
}
