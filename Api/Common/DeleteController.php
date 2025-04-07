<?php

namespace Zoomx\Controllers\Api\Common;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CommonController;
use Zoomx\Controllers\Api\Common\CommonTrait;

class DeleteController extends CommonController
{
  use CommonTrait;

  private function handlePricelistItem($class, $data, $item)
  {
    $itemData = $item->toArray();
    $output = $item->remove() ? $data : $itemData;

    if($class !== \pricelistItem::class || $itemData[Constants::GROUP_KEY] > 0) {
      return $output;
    }

    $resources = [];
    $pricelistLinks = $this->modx->getCollection(\pricelistLink::class);

    foreach ($pricelistLinks as $pricelistLinkItem) {
      $linkItem = $pricelistLinkItem->toArray();
      $config = json_decode($linkItem[Constants::CONFIG_KEY], true);
      $pricelist = json_decode($linkItem[Constants::ITEMS_PARAM_KEY], true);

      if($config['isComplexData'] === true && in_array($itemData[Constants::ID_KEY], $pricelist)) {
        $resources[] = $linkItem[Constants::ID_KEY];
      }
    }

    if(count($resources) > 0) {
      zoomx('modx')->runSnippet('sendResLinksData', array(
        'resources' => $resources,
        'item' => $itemData
      ));
    }

    return $output;
  }

  private function deleteItem($data, $dateKey, $class)
  {
    $output = [];
    $item = $this->getItem($class, $data);

    if ((bool)$item) {
      $output = $this->handlePricelistItem($class, $data, $item);
      zoomx('modx')->cacheManager->clearCache();
    } else {
      $output = $data;
      $output[$dateKey] = $item;
    }

    return $output;
  }

  public function handleItem($data, $dateKey, $class)
  {
    return $this->deleteItem($data, $dateKey, $class);
  }

  protected function deleteData($class)
  {
    return $this->handleData(
      $class,
      Constants::UPDATEDON_KEY,
      Constants::VALUES_DELETE_ERROR_MSG
    );
  }
}
