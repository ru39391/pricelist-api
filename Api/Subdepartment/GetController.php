<?php

namespace Zoomx\Controllers\Api\Subdepartment;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CommonController;

class GetController extends CommonController
{
  public function index()
  {
    return $this->getItems(
      \pricelistSubdept::class,
      [
        Constants::GROUPS_PARAM_KEY => \pricelistGroup::class
      ],
      Constants::SUBDEPT_KEY
    );
  }
}
