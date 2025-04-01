<?php

namespace Zoomx\Controllers\Api\Resource;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\AuthController;
use Zoomx\Controllers\Api\Common\CommonTrait;

class CreateController extends AuthController
{
  use CommonTrait;

  private function createItem($arr, $class)
  {
    $output = [];

    foreach ($arr as $data) {
      $item = zoomx('modx')->getObject($class, array('id' => $data['id']));

      if((bool)$item) {
        $response = $item->toArray();
        $response[Constants::CREATEDON_KEY] = null;

        $output[] = $response;
      } else {
        $response = zoomx('modx')->newObject($class, $data);
        $response->save();
        zoomx('modx')->cacheManager->clearCache();

        $output[] = $response->toArray();
      }
    }

    return $output;
  }

  public function index($isTemplate = 0)
  {
    $result = [];
    $output = array(
      'succeed' => [],
      'existed' => []
    );
    $items = $this->getInputData();
    $class = (int)$isTemplate > 0 ? \modTemplate::class : \modResource::class;

    for ($i = 0; $i < count($items); $i += 100) {
      $arr = array_slice($items, $i, 100);
      $result[] = $this->createItem($arr, $class);
    }

    foreach (array_merge(...$result) as $data) {
      if($data[Constants::CREATEDON_KEY]) {
        $output['succeed'][] = $data;
      } else {
        $output['existed'][] = $data;
      }
    }

    $output['counter'] = array(
      'succeed' => count($output['succeed']),
      'existed' => count($output['existed'])
    );

    $responseCode = count($output['succeed']) > 0 ? 200 : 400;

    return jsonx($output, [], $responseCode);
  }
}
