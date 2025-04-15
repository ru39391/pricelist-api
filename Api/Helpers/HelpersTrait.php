<?php

namespace Zoomx\Controllers\Api\Helpers;

use Zoomx\Controllers\Constants;

trait HelpersTrait
{
  public function setItemsData($item)
  {
    $url = '';
    $isWebLink = $item['class_key'] === 'modWebLink';
    $isIdLinkExist = (int)$item['content'] > 0;

    if($isWebLink) {
      $data = $isIdLinkExist ? $this->modx->getObject(\modResource::class, (int)$item['content']) : $item['content'];
      $url =  $isIdLinkExist ? $data->uri : $data;
    }

    return array(
      'id' => $item['id'],
      'menuindex' => $item['menuindex'],
      'menutitle' => $item['menutitle'],
      'pagetitle' => $item['pagetitle'],
      'uri' => $isWebLink ? $url : $item['uri'],
      'classKey' => $item['class_key'],
      'properties' => $item['properties']
    );
  }

  public function setItemsArr($parent = 0, $picIds = [3,4], $sortBy = 'menuindex', $sortDir = 'ASC')
  {
    $items = [];

    $where = array(
      'parent' => $parent,
      'deleted' => 0,
      'hidemenu' => 0,
      'published' => 1
    );
    $itemsList = $this->modx->getCollection(\modResource::class, $where);

    foreach ($itemsList as $data) {
      $isDataExist = count($picIds) === 0;
      $pictures = array_map(fn($picId) => $data->getTVValue($picId), $picIds);

      if($isDataExist || array_reduce($pictures, fn($carry, $item) => $carry && (bool)$item, true)) {
        $items[] = array_merge(
          $this->setItemsData($data->toArray()),
          array(
            'publishedon' => $data->publishedon,
            'pictures' => array_map(
              fn($item) => array(
                'src' => $item,
                'thumb' => $this->modx->runSnippet('phpthumbon', array('input' => $item, 'options' => 'f=webp'))
              ),
              $pictures
            )
          )
        );
      }
    }

    if($sortDir === 'ASC') {
      usort($items, fn($a, $b) => $a[$sortBy] - $b[$sortBy]);
    } else {
      usort($items, fn($a, $b) => $b[$sortBy] - $a[$sortBy]);
    }

    return $items;
  }
}
