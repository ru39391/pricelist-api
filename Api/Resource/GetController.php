<?php

namespace Zoomx\Controllers\Api\Resource;

use Zoomx\Controllers\Api\AuthController;

class GetController extends AuthController
{
  public function index()
  {
    $response = [];
    $pages = [];
    $features = [];
    $parentsList = [];
    $templates = [];
    $tplIds = [];

    $props = array('deleted' => 0, 'published' => 1);
    $pubResourcesList = $this->modx->getCollection(\modResource::class, $props);
    /*
    8 - ДЕТСКОЕ ОТДЕЛЕНИЕ;
    40 - Отзывы;
    168 - Рефлексотерапия;
    230 - Хайлайты;
    240 - Тестирование и разработка;
    243 - Технические страницы;
    356 - Примеры работ;
    */
    $unpubParentsList = $this->modx->getCollection(
      \modResource::class,
      array('id:IN' => [8,168,230,240])
    );
    $resourcesList = array_merge($pubResourcesList, $unpubParentsList);

    if (count($resourcesList) > 0) {
      foreach ($resourcesList as $res) {
        $data = $res->toArray();

        $parentsList[] = $res->parent;
        $tplIds[] = $res->template;

        if(in_array($res->parent, [40,230,240,243,356])) {
          $features[] = $data;
        } else {
          $pages[] = $data;
        }
      }

      foreach ($pages as $key => $page) {
        if(in_array($page['parent'], array_map(fn($data) => $data['id'], $features))) {
          unset($pages[$key]);
          $features = array_merge($features, array($page));
        }
      }

      $templatesList = $this->modx->getCollection(
        \modTemplate::class,
        array('id:IN' => array_unique($tplIds))
      );

      foreach ($templatesList as $tpl) {
        $templates[] = $tpl->toArray();
      }

      foreach ([$pages, $features, $templates] as $arr) {
        usort($arr, fn($a, $b) => $a['id'] - $b['id']);
      }

      $parents = array_unique($parentsList);
      usort($parents, fn($a, $b) => $a - $b);

      $response = array(
        'counter' => array(
          'templates' => count($templates),
          'resources' => count($pages) + count($features),
          'features' => count($features),
          'pages' => count($pages),
          'parents' => count($parents),
        ),
        'pages' => [...$pages],
        'features' => $features,
        'parents' => $parents,
        'templates' => $templates,
        'success' => true
      );
    }

    $responseCode = $response['success'] ? 200 : 400;

    return jsonx($response, [], $responseCode);
  }
}
