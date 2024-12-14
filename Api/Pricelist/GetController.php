<?php

namespace Zoomx\Controllers\Api\Pricelist;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\GetController as CommonGetController;

class GetController extends CommonGetController
{
  public function index()
  {
    return $this->getItems(\pricelistItem::class);
  }
}
