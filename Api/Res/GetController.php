<?php

namespace Zoomx\Controllers\Api\Res;

use Zoomx\Controllers\Api\AuthController;

class GetController extends AuthController
{
  private function getParent($id) {
    $res = $this->modx->getObject(\modResource::class, array('id' => $id));

    return array(
      'parent_id' => $res->id,
      'name' => trim($res->pagetitle),
      'uri' => $res->uri,
    );
  }

  public function index()
  {
    $query = $this->modx->newQuery(\modResource::class);
    $query->select(array(
      'modResource.id as id',
      'modResource.pagetitle as pagetitle',
      'modResource.parent as parent',
      'modResource.publishedon as publishedon',
      'modResource.editedon as editedon',
      'modResource.isfolder as isfolder',
      'modResource.template as template',
      'modResource.uri as uri',
      'modResource.deleted as deleted',
      'modResource.published as published',
      'template.id as template_id',
      'template.description as template_desc'
    ));
    $query->leftJoin(\modTemplate::class, 'template', 'modResource.template = template.id');
    $query->groupby('modResource.id');
    $query->where(array(
      'modResource.deleted' => 0,
      'modResource.published' => 1,
      // TODO: изменить id
      'modResource.template:IN' => array(8,9,15,16,17,18,19,20),
      // TODO: изменить id
      'modResource.parent:NOT IN' => array(0,3,5,37,40,230,243,356),
    ));

    $resourcesList = $this->modx->getCollection(\modResource::class, $query);

    usort($resourcesList, fn($a, $b) => $b->publishedon - $a->publishedon);

    if (count($resourcesList) > 0) {
      $resources = array_map(fn($item) => array(
        'id' => $item->id,
        'isParent' => (bool)$item->isfolder,
        'name' => trim($item->pagetitle),
        'uri' => $item->uri,
        'parent' => $this->getParent($item->parent),
        'template' => array(
          'template_id' => (int)$item->template_id,
          'name' => $item->template_desc
        ),
        'publishedon' => array(
          'value' => $item->publishedon,
          'date' => date('d.m.Y H:i', $item->publishedon)
        ),
        'editedon' => array(
          'value' => $item->editedon,
          'date' => date('d.m.Y H:i', $item->editedon)
        )
      ), $resourcesList);

      return jsonx($resources);
    }

    return jsonx([], [], 404);
  }
}
