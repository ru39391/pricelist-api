<?php

namespace Zoomx\Controllers\Api\Pricelist;

use Zoomx\Controllers\Api\Common\DeleteController as CommonDeleteController;

class DeleteController extends CommonDeleteController
{
  public function index()
  {
    return $this->deleteData(\pricelistItem::class);
  }
}
