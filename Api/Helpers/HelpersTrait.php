<?php

namespace Zoomx\Controllers\Api\Helpers;

use Zoomx\Controllers\Constants;

trait HelpersTrait
{
  private function setUri($item)
  {
    $url = '';
    $isWebLink = $item['class_key'] === 'modWebLink';
    $isIdLinkExist = (int)$item['content'] > 0;

    if($isWebLink) {
      $data = $isIdLinkExist ? $this->modx->getObject(\modResource::class, (int)$item['content']) : $item['content'];
      $url =  $isIdLinkExist ? $data->uri : $data;
    }

    return $isWebLink ? $url : $item['uri'];
  }

  public function setItemsData($item)
  {
    $url = $this->setUri($item);

    if (substr($url, -1) === '/') {
      $url = rtrim($url, '/');
    }

    return array(
      'id' => $item['id'],
      //'alias' => $item['alias'],
      'menuindex' => $item['menuindex'],
      'menutitle' => $item['menutitle'],
      'pagetitle' => $item['pagetitle'],
      'uri' => $url,
      'class_key' => $item['class_key'],
      'properties' => $item['properties']
    );
  }

  // TODO: изменить id
  public function setItemsArr($isNav, $parent, $picIds, $sortBy = 'menuindex', $sortDir = 'ASC')
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
      $pictures = $isDataExist ? [] : array_map(fn($picId) => $data->getTVValue($picId), $picIds);

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

    usort(
      $items,
      fn($a, $b) => $sortDir === 'DESC' ? $b[$sortBy] - $a[$sortBy] : $a[$sortBy] - $b[$sortBy]
    );

    return $items;
  }

  public function setNavItems()
  {
    return $this->setItemsArr(true, 0, [3,4]);
  }
}
