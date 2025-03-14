<?php

namespace Zoomx\Controllers\Api\Resource;

use Zoomx\Controllers\Api\AuthController;

class GetController extends AuthController
{

  public function index()
  {
    $parents = [];
    $resources = [];
    $templates = [];
    $tplIds = [];

    $props = array(
      'deleted' => 0,
      'published' => 1,
    );
    $resourcesList = $this->modx->getCollection(\modResource::class, $props);

    usort($resourcesList, fn($a, $b) => $a->id - $b->id);

    if (count($resourcesList) > 0) {
      foreach ($resourcesList as $res) {
        $data = $res->toArray();

        $resources[] = $data;
        $parents[] = $res->parent;
        $tplIds[] = $res->template;
      }

      $templatesList = $this->modx->getCollection(
        \modTemplate::class,
        array('id:IN' => array_unique($tplIds))
      );

      foreach ($templatesList as $tpl) {
        $templates[] = $tpl->toArray();
      }

      return array(
        'counter' => array(
          'parents' => count(array_unique($parents)),
          'resources' => count($resources),
        ),
        'templates' => $templates,
        //'parents' => array_unique($parents),
        //'resources' => $resources,
      );
    }

    return jsonx([], [], 404);
  }
}
