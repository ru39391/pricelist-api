<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CommonController;

class GetController extends CommonController
{
  public function index()
  {
    return $this->getItems(
      \pricelistDept::class,
      [
        Constants::SUBDEPTS_PARAM_KEY => \pricelistSubdept::class,
        Constants::GROUPS_PARAM_KEY => \pricelistGroup::class
      ],
      Constants::DEPT_KEY
    );
  }
}
