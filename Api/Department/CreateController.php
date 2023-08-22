<?php

namespace Zoomx\Controllers\Api\Department;

use Zoomx\Controllers\Api\AuthController as AuthController;

class CreateController extends AuthController
{
  private function createDept($data)
  {
    $response = zoomx('modx')->newObject(\pricelistDept::class, $data);
    $response->save();
    zoomx('modx')->cacheManager->clearCache();

    return $response->toArray();
  }

  public function index()
    {
      $data = json_decode(file_get_contents('php://input'), true);
      list($item_id, $name) = [(int)$data['item_id'], trim($data['name'])];

      $validatedData = [
        'item_id' => (empty($item_id) || !is_numeric($item_id) || $item_id <= 0) ? false : $item_id,
        'name' => (empty($name) || mb_strlen($name) > 255) ? false : $name,
      ];

      if (in_array(false, $validatedData, true)) {
        $response = [
          'success' => false,
          'message' => 'Неверный формат введённых данных'
        ];
      } else {
        $response = $this->createDept([
          'success' => true,
          'item_id' => $validatedData['item_id'],
          'name' => $validatedData['name']
        ]);
      }

      $responseCode = $response['success'] ? 201 : 400;

      return jsonx($response, [], $responseCode);
    }
}
