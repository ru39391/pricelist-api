<?php

namespace Zoomx\Controllers\Api\Pricelist;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CreateController as CommonCreateController;

class CreateController extends CommonCreateController
{
  public function index()
  {
    return $this->createData(
      \pricelistItem::class,
      Constants::ITEM_KEYS
    );
  }
}
