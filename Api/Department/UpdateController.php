<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\Api\AuthController;
use Zoomx\Controllers\Api\Department\DeptsTrait;

class UpdateController extends AuthController
{
  use DeptsTrait;

  private function updateDept($data)
  {
  }

  public function index()
  {
    $response = [];
    $data = $this->getData();
    $validatedData = array_map(fn($item) => $this->validateData($item), $data);

    if (in_array(null, $validatedData, true)) {
    }

    $responseCode = $response['success'] ? 201 : 400;

    return jsonx($response, [], $responseCode);
  }
}
