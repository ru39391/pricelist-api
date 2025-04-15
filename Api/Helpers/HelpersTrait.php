<?php

namespace Zoomx\Controllers\Api\Helpers;

use Zoomx\Controllers\Constants;

trait HelpersTrait
{
  public function setNavData($item)
  {
    return array(
      'id' => $item['id'],
      'menuindex' => $item['menuindex'],
      'menutitle' => $item['menutitle'],
      'pagetitle' => $item['pagetitle'],
      'uri' => $item['uri'],
    );
  }

  public function setNavItems()
  {
    $nav = [];

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
        $nav[] = array_merge(
          $this->setNavData($data->toArray()),
          array(
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

    usort($nav, fn($a, $b) => $b->menuindex - $a->menuindex);

    return $nav;
  }
}
