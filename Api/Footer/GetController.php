<?php

namespace Zoomx\Controllers\Api\Footer;

use Zoomx\Controllers\Constants;
use Zoomx\Controllers\BaseController;
use Zoomx\Controllers\Api\Helpers\HelpersTrait;

class GetController extends BaseController
{
  use HelpersTrait;

  public function index()
  {
    $nav = $this->setNavItems();
    $docsNav = [];
    $panelNav = [];
    $address = [];
    $social = [];

    $where = array(
      'parent' => 0,
      'deleted' => 0,
      'published' => 1,
    );
    $mainNavList = $this->modx->getCollection(
      // TODO: изменить id
      \modResource::class, array_merge($where, array('id:IN' => array(40,1494)))
    );
    $docsNavList = $this->modx->getCollection(
      // TODO: изменить id
      \modResource::class, array_merge($where, array('id:IN' => array(594,595,597)))
    );
    $panelNavList = $this->modx->getCollection(
      // TODO: изменить id
      \modResource::class, array_merge($where, array('id:IN' => array(325,40)))
    );

    foreach ($mainNavList as $data) {
      $nav[] = $data->toArray();
    }

    foreach ($docsNavList as $data) {
      $docsNav[] = $this->setItemsData($data->toArray());
    }

    foreach ($panelNavList as $data) {
      $panelNav[] = $this->setItemsData($data->toArray());
    }

    // TODO: перенести координаты в сис. настройки
    foreach (['zip', 'region', 'city', 'address'] as $key) {
      $address[$key] = $this->modx->getOption('default__contacts_' . $key);
    }

    // TODO: поправить ссылки соц. сетей
    foreach (['ok', 'tg', 'vk'] as $key) {
      $social[$key] = $this->modx->getOption('default__contacts_' . $key);
    }

    $copyright = array(
      'year' => date('Y'),
      'name' => $this->modx->getOption('site_name'),
      'desc' => 'Все права защищены. Мы в соцсетях'
    );

    // TODO: перенести расписание в сис. настройки
    $contacts = array(
      'phone' => $this->modx->getOption('default__phones_main'),
      'email' => $this->modx->getOption('default__contacts_email'),
      'open' => array(
        ['key' => 'Mo', 'name' => 'Пн', 'start' => '08:00', 'end' => '21:00'],
        ['key' => 'Sa', 'name' => 'Вс', 'start' => '08:00', 'end' => '21:00']
      )
    );

    usort($nav, fn($a, $b) => $a['menuindex'] - $b['menuindex']);

    $mainNav = array_map(
      fn($item) => $this->setItemsData($item),
      // TODO: изменить id
      array_filter($nav, fn($item) => $item['id'] !== 6)
    );

    $response = array(
      'address' => $address,
      'contacts' => $contacts,
      'social' => $social,
      'copyright' => $copyright,
      'nav' => array(
        'main' => [...$mainNav],
        'docs' => $docsNav,
        'panel' => $panelNav
      )
    );

    return jsonx($response, [], 200);
  }
}
