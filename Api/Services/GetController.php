<?php

namespace Zoomx\Controllers\Api\Services;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\BaseController;
use Zoomx\Controllers\Api\Helpers\HelpersTrait;

class GetController extends BaseController
{
  use HelpersTrait;

  public function index()
  {
    $depts = [];

    $where = array(
      // TODO: изменить id
      'parent' => 2,
      'deleted' => 0
    );
    $deptsList = $this->modx->getCollection(\modResource::class, $where);

    foreach ($deptsList as $data) {
      // TODO: изменить id
      $children = $this->setItemsArr(false, $data->id, [11], 'publishedon', 'DESC');

      $depts[] = array_merge(
        $this->setItemsData($data->toArray()),
        array('children' => $children)
      );
    }

    return jsonx($depts, [], 200);
  }
}
