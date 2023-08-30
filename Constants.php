<?php

namespace Zoomx\Controllers;

class Constants
{
  const ID_KEY = 'item_id';
  const NAME_KEY = 'name';
  const DEPT_KEY = 'dept';
  const IS_VALID_KEY = 'isValid';
  const UPDATEDON_KEY = 'updatedon';
  const CREATEDON_KEY = 'createdon';

  const SUBDEPTS_PARAM_KEY = 'subdepts';
  const GROUPS_PARAM_KEY = 'groups';

  const VALUES_ERROR_KEY = 'error_values';

  const DATA_ERROR_MSG = 'Некорректный формат передаваемых данных';
  const VALUES_CREATE_ERROR_MSG = 'Нет подходящих для добавления элементов, проверьте их значения';
  const VALUES_UPDATE_ERROR_MSG = 'Обновление невозможно: элементов с такими ID не существует, либо их значения переданы некорректно';
  const VALUES_DELETE_ERROR_MSG = 'Невозможно удалить записи: не найдено элементов с указанными ID, либо их значения переданы некорректно';
}
