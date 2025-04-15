<?php

namespace Zoomx\Controllers\Api\Header;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\BaseController;

class GetController extends BaseController
{
  public function index()
  {
    $address = [];
    $phones = [];
    $nav = [];
    $options = [];

    $where = array(
      'parent' => 0,
      'deleted' => 0,
      'hidemenu' => 0,
      'published' => 1
    );
    $navList = $this->modx->getCollection(\modResource::class, $where);

    foreach ($navList as $data) {
      $pictures = [$data->getTVValue(3), $data->getTVValue(4)];

      if(array_reduce($pictures, fn($carry, $item) => $carry && (bool)$item, true)) {
        $nav[] = array(
          'id' => $data->id,
          'menuindex' => $data->menuindex,
          'menutitle' => $data->menutitle,
          'pagetitle' => $data->pagetitle,
          'uri' => $data->uri,
          'pictures' => array_map(
            fn($item) => array(
              'src' => $item,
              'thumb' => $this->modx->runSnippet('phpthumbon', array('input' => $item, 'options' => 'f=webp'))
            ),
            $pictures
          )
        );
      }
    }

    foreach (['city', 'address'] as $key) {
      $address[$key] = $this->modx->getOption('default__contacts_' . $key);
    }

    foreach (['main', 'extra', 'mobile'] as $key) {
      $phones[$key] = $this->modx->getOption('default__phones_' . $key);
    }

    foreach (['url', 'name'] as $key) {
      $options[$key] = $this->modx->getOption('site_' . $key);
    }

    $response = array_merge(
      $options,
      array(
        'address' => $address,
        'phones' => $phones,
        'nav' => $nav,
      )
    );

    return jsonx($response, [], 200);
  }
}
