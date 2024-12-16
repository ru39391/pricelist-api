<?php

namespace Zoomx\Controllers\Api\Reslink;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CreateController as CommonCreateController;

class CreateController extends CommonCreateController
{
  public function index()
  {
    return $this->createData(
      \pricelistLink::class,
      Constants::RESLINK_KEYS
    );
  }
}
