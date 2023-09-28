<?php

namespace Zoomx\Controllers\Api\Subdepartment;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CreateController as CommonCreateController;

class CreateController extends CommonCreateController
{
  public function index()
  {
    return $this->createData(
      \pricelistSubdept::class,
      Constants::SUBDEPT_KEYS
    );
  }
}
