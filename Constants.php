<?php

namespace Zoomx\Controllers;

class Constants
{
  const ID_KEY = 'item_id';
  const NAME_KEY = 'name';
  const DEPT_KEY = 'dept';
  const SUBDEPT_KEY = 'subdept';
  const GROUP_KEY = 'group';
  const PRICE_KEY = 'price';
  const IS_VALID_KEY = 'isValid';
  const UPDATEDON_KEY = 'updatedon';
  const CREATEDON_KEY = 'createdon';

  const IS_COMPLEX_ITEM_KEY = 'isComplexItem';
  const IS_COMPLEX_KEY = 'isComplex';
  const COMPLEX_KEY = 'complex';
  const IS_VISIBLE_KEY = 'isVisible';
  const INDEX_KEY = 'index';
  const CONFIG_KEY = 'config';

  const DEPTS_PARAM_KEY = 'depts';
  const SUBDEPTS_PARAM_KEY = 'subdepts';
  const GROUPS_PARAM_KEY = 'groups';
  const ITEMS_PARAM_KEY = 'pricelist';

  const IS_COMPLEX_DATA_KEY = 'isComplexData';
  const IS_GROUP_IGNORED_KEY = 'isGroupIgnored';

  const DEPT_KEYS = [
    Constants::ID_KEY,
    Constants::NAME_KEY
  ];
  const SUBDEPT_KEYS = [
    Constants::ID_KEY,
    Constants::NAME_KEY,
    Constants::DEPT_KEY
  ];
  const GROUP_KEYS = [
    Constants::ID_KEY,
    Constants::NAME_KEY,
    Constants::DEPT_KEY,
    Constants::SUBDEPT_KEY,
    Constants::GROUP_KEY
  ];
  const ITEM_KEYS = [
    Constants::ID_KEY,
    Constants::NAME_KEY,
    Constants::PRICE_KEY,
    Constants::DEPT_KEY,
    Constants::SUBDEPT_KEY,
    Constants::GROUP_KEY,
    Constants::IS_COMPLEX_ITEM_KEY,
    Constants::IS_COMPLEX_KEY,
    Constants::COMPLEX_KEY,
    Constants::IS_VISIBLE_KEY,
    Constants::INDEX_KEY,
  ];
  const RESLINK_KEYS = [
    Constants::ID_KEY,
    Constants::DEPTS_PARAM_KEY,
    Constants::SUBDEPTS_PARAM_KEY,
    Constants::GROUPS_PARAM_KEY,
    Constants::ITEMS_PARAM_KEY,
    Constants::CONFIG_KEY
  ];

  const VALUES_ERROR_KEY = 'error_values';

  const DATA_ERROR_MSG = 'Некорректный формат передаваемых данных';
  const VALUES_CREATE_ERROR_MSG = 'Нет подходящих для добавления элементов, проверьте их значения';
  const VALUES_UPDATE_ERROR_MSG = 'Обновление невозможно: элементов с такими ID не существует, либо их значения переданы некорректно';
  const VALUES_DELETE_ERROR_MSG = 'Невозможно удалить записи: не найдено элементов с указанными ID, либо их значения переданы некорректно';
}
