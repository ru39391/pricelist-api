<?php

namespace Zoomx\Controllers\Api\Group;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CommonController;

class GetController extends CommonController
{
  public function index()
  {
    return $this->getItems(\pricelistGroup::class);
  }
}
