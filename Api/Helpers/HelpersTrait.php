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
      //'alias' => $item['alias'],
      'menuindex' => $item['menuindex'],
      'menutitle' => $item['menutitle'],
      'pagetitle' => $item['pagetitle'],
      'uri' => $isWebLink ? $url : $item['uri'],
      'classKey' => $item['class_key'],
      'properties' => $item['properties']
    );
  }

  // TODO: изменить id
  public function setItemsArr($isNav = true, $parent = 0, $picIds = [3,4], $sortBy = 'menuindex', $sortDir = 'ASC')
  {
    $items = [];

    $where = array(
      'deleted' => 0,
      'published' => 1,
      'parent' => $parent
    );
    $itemsList = $this->modx->getCollection(
      \modResource::class,
      $isNav ? array_merge($where, array('hidemenu' => 0)) : $where
    );

    foreach ($itemsList as $data) {
      $isDataExist = count($picIds) === 0;
      $pictures = array_map(fn($picId) => $data->getTVValue($picId), $picIds);

      if($isDataExist || array_reduce($pictures, fn($carry, $item) => $carry && (bool)$item, true)) {
        $items[] = array_merge(
          $this->setItemsData($data->toArray()),
          array(
            'pictures' => array_map(
              fn($item) => array(
                'src' => $item,
                'thumb' => $this->modx->runSnippet('phpthumbon', array('input' => $item, 'options' => 'f=webp'))
              ),
              $pictures
            ),
            'publishedon' => $data->publishedon,
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
