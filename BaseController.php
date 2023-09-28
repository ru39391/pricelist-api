<?php

namespace Zoomx\Controllers;

use modX;

abstract class BaseController
{
    protected $modx;

    public function __construct(modX $modx)
    {
      $this->modx = $modx;
      $this->disableAutoloadRes();
    }

    private function disableAutoloadRes()
    {
      zoomx()->autoloadResource(false);
    }
}
