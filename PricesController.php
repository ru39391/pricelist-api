<?php

namespace Zoomx\Controllers;

class PricesController extends BaseController
{
  public function index()
  {
    if ($this->modx->user->isMember('Administrator')) {
      $data = zoomx()->getResource(2365);

      return viewx('app.tpl', $data->toArray());
    } else {
      return redirectx(zoomx()->getResource(43)->alias, 301);
    }
  }
}
