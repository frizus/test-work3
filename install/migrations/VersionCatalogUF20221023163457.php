<?php

namespace Sprint\Migration;


class VersionCatalogUF20221023163457 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog_and_news:catalog_SECTION',
  'FIELD_NAME' => 'UF_NEWS_LINK',
  'USER_TYPE_ID' => 'iblock_element',
  'XML_ID' => 'UF_NEWS_LINK',
  'SORT' => '100',
  'MULTIPLE' => 'Y',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DISPLAY' => 'LIST',
    'LIST_HEIGHT' => 5,
    'IBLOCK_ID' => 'catalog_and_news:news',
    'DEFAULT_VALUE' => '',
    'ACTIVE_FILTER' => 'N',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Классификатор новости',
    'ru' => 'Классификатор новости',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Классификатор новости',
    'ru' => 'Классификатор новости',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Классификатор новости',
    'ru' => 'Классификатор новости',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
    }

    public function down()
    {
        //your code ...
    }
}
