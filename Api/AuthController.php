<?php

namespace Zoomx\Controllers\Api;

use modX;
//use Zoomx\Controllers\Api\BaseController as BaseController;

class AuthController extends BaseController
{
  public function __construct(modX $modx)
  {
    parent::__construct($modx);
    //$this->adminAuth();
  }

  private function adminAuth()
  {
    if (!$this->modx->user->isMember('Administrator')) {
      abortx(401);
    }
  }
}
