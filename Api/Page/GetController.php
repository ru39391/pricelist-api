<?php

namespace Zoomx\Controllers\Api\Page;

use Zoomx\Controllers\Api\AuthController as AuthController;

class GetController extends AuthController
{
  private function getArr($str) {
    return (bool)$str ? array_map(fn($item) => (int)$item, explode(',', (string)$str)) : array();
  }

  public function index()
  {
    $resColl = $this->modx->getCollection(\modResource::class, array(
      'deleted' => 0,
      'published' => 1,
      'template:IN' => array(8,9,15,16,17,18,19,20),
      'parent:NOT IN' => array(0,3,5,37,40,230,243,356),
    ));
    if (count($resColl) > 0) {
      $pages = array_map(fn($item) => array(
        'id' => $item->id,
        'pagetitle' => $item->pagetitle,
        'parent' => $item->parent,
        //'department_id' => $this->getArr($item->getTVValue('department_id')),
        //'specialization_ids' => $this->getArr($item->getTVValue('specialization_ids')),
        //'group_ids' => $this->getArr($item->getTVValue('group_ids')),
        //'show_pl' => (bool)$item->getTVValue('tv__show_pricelist'),
        'publishedon' => $item->publishedon,
        'editedon' => $item->editedon,
        'isfolder' => $item->isfolder,
        'template' => $item->template,
        'uri' => $item->uri,
      ), $resColl);

      return jsonx($pages);
    }

    return jsonx([], [], 404);
  }
}
