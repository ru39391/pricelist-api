<?php

namespace Zoomx\Controllers;

class ServicesController extends BaseController
{
  public function index()
  {
    if ($this->modx->user->isMember('Administrator')) {
      return viewx('services.tpl');
    } else {
      return redirectx(zoomx()->getResource(43)->alias, 301);
    }
  }
}
