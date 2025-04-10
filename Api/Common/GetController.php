<?php

namespace Zoomx\Controllers\Api\Common;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\BaseController;
use Zoomx\Controllers\Api\Common\CommonTrait;

class GetController extends BaseController
{
  use CommonTrait;

  protected function getItems($class, $children = [], $dataKey = '')
  {
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/pricelist/model/pricelist/');

    $response = [];
    $payload = $this->getInputData();
    $resources = $this->modx->getCollection($class);

    foreach($resources as $item) {
      $response[] = $item->toArray();
    }

    $responseCode = 200;
    $items = array_reduce(
      $response,
      fn($array, $item) => in_array($item[Constants::ID_KEY], $payload) ? array_merge($array, [$item]) : $array,
      []
    );

    return jsonx(count($payload) > 0 ? $items : $response, [], $responseCode);
  }
}
