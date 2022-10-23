<?php

namespace Sprint\Migration;


class VersionCatalogFillSections20221023163538 extends Version
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

        $iblockId = $helper->Iblock()->getIblockIdIfExists(
            'catalog',
            'catalog_and_news'
        );

        $helper->Iblock()->addSectionsFromTree(
            $iblockId,
            array (
  0 => 
  array (
    'NAME' => 'Офисная мебель',
    'CODE' => NULL,
    'SORT' => '100',
    'ACTIVE' => 'Y',
    'XML_ID' => '1',
    'DESCRIPTION' => NULL,
    'DESCRIPTION_TYPE' => 'text',
    'UF_NEWS_LINK' => 
    array (
      0 => 29,
      1 => 30,
      2 => 31,
    ),
  ),
  1 => 
  array (
    'NAME' => 'Мягкая мебель',
    'CODE' => NULL,
    'SORT' => '200',
    'ACTIVE' => 'Y',
    'XML_ID' => '3',
    'DESCRIPTION' => NULL,
    'DESCRIPTION_TYPE' => 'text',
    'UF_NEWS_LINK' => 
    array (
      0 => 30,
      1 => 31,
    ),
  ),
  2 => 
  array (
    'NAME' => 'Мебель для кухни',
    'CODE' => NULL,
    'SORT' => '300',
    'ACTIVE' => 'Y',
    'XML_ID' => '2',
    'DESCRIPTION' => NULL,
    'DESCRIPTION_TYPE' => 'text',
    'UF_NEWS_LINK' => 
    array (
      0 => 29,
      1 => 30,
    ),
  ),
  3 => 
  array (
    'NAME' => 'Детская мебель',
    'CODE' => NULL,
    'SORT' => '400',
    'ACTIVE' => 'Y',
    'XML_ID' => '4',
    'DESCRIPTION' => NULL,
    'DESCRIPTION_TYPE' => 'text',
    'UF_NEWS_LINK' => 
    array (
      0 => 30,
      1 => 31,
    ),
  ),
)        );
    }

    public function down()
    {
        //your code ...
    }
}
