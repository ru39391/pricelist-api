<?php

namespace Zoomx\Controllers\Api\Res;

use Zoomx\Controllers\Api\AuthController;

class GetController extends AuthController
{
  private function getArr($str) {
    return (bool)$str ? array_map(fn($item) => (int)$item, explode(',', (string)$str)) : array();
  }

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
      /*
      'department_id.value as dept_id',
      'specialization_ids.value as subdept_id',
      'group_ids.value as group_id',
      */
    ));
    $query->leftJoin(\modTemplate::class, 'template', 'modResource.template = template.id');
    /*
    $query->leftJoin(\modTemplateVarResource::class, 'department_id', 'modResource.id = department_id.contentid AND department_id.tmplvarid = 25');
    $query->leftJoin(\modTemplateVarResource::class, 'specialization_ids', 'modResource.id = specialization_ids.contentid AND specialization_ids.tmplvarid = 26');
    $query->leftJoin(\modTemplateVarResource::class, 'group_ids', 'modResource.id = group_ids.contentid AND group_ids.tmplvarid = 28');
    */
    $query->groupby('modResource.id');
    $query->where(array(
      'modResource.deleted' => 0,
      'modResource.published' => 1,
      'modResource.template:IN' => array(8,9,15,16,17,18,19,20),
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
        /*
        'dept' => $this->getArr($item->dept_id),
        'subdept' => $this->getArr($item->subdept_id),
        'group' => $this->getArr($item->group_id),
        */
      ), $resourcesList);

      return jsonx($resources);
    }

    return jsonx([], [], 404);
  }
}
