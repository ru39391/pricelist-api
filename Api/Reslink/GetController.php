<?php

namespace Zoomx\Controllers\Api\Reslink;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CommonController;

class GetController extends CommonController
{
  public function index()
  {
    return $this->getItems(\pricelistLink::class);
  }
}
