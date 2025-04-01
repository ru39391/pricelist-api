<?php

namespace Zoomx\Controllers\Api\Resource;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\Common\CreateController as CommonCreateController;

class CreateController extends CommonCreateController
{
  public function index()
  {
    $response = array(
      'success' => true
    );

    $responseCode = $response['success'] ? 200 : 400;

    return jsonx($response, [], $responseCode);
  }
}
