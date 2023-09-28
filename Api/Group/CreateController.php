<?php

namespace Zoomx\Controllers\Api\Group;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CreateController as CommonCreateController;

class CreateController extends CommonCreateController
{
  public function index()
  {
    return $this->createData(
      \pricelistGroup::class,
      Constants::GROUP_KEYS
    );
  }
}
