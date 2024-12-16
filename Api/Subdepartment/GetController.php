<?php

namespace Zoomx\Controllers\Api\Subdepartment;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\GetController as CommonGetController;

class GetController extends CommonGetController
{
  public function index()
  {
    return $this->getItems(\pricelistSubdept::class);
  }
}
