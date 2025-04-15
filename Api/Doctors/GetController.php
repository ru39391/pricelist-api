<?php

namespace Zoomx\Controllers\Api\Doctors;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\BaseController;
use Zoomx\Controllers\Api\Helpers\HelpersTrait;

class GetController extends BaseController
{
  use HelpersTrait;

  public function index()
  {
    // TODO: изменить id
    // TODO: добавить обработку доп. параметров
    $doctors = $this->setItemsArr(false, 3, [6], 'publishedon');

    return jsonx($doctors, [], 200);
  }
}
