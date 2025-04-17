<?php

namespace Zoomx\Controllers\Api\Header;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\BaseController;
use Zoomx\Controllers\Api\Helpers\HelpersTrait;

class GetController extends BaseController
{
  use HelpersTrait;

  public function index()
  {
    $address = [];
    $phones = [];
    $site = [];

    foreach (['city', 'address'] as $key) {
      $address[$key] = $this->modx->getOption('default__contacts_' . $key);
    }

    foreach (['main', 'extra', 'mobile'] as $key) {
      $phones[$key] = $this->modx->getOption('default__phones_' . $key);
    }

    foreach (['url', 'name'] as $key) {
      $site[$key] = $this->modx->getOption('site_' . $key);
    }

    $response = array(
      'site' => $site,
      'address' => $address,
      'phones' => $phones,
      'nav' => $this->setNavItems(),
    );

    return jsonx($response, [], 200);
  }
}
