<?php

use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!Loader::includeModule('iblock')) {
    return;
}

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arCatalogIBlock = [];
$iblockFilter = !empty($arCurrentValues['CATALOG_IBLOCK_TYPE'])
    ? ['TYPE' => $arCurrentValues['CATALOG_IBLOCK_TYPE'], 'ACTIVE' => 'Y']
    : ['ACTIVE' => 'Y'];
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch())
{
    $id = (int)$arr['ID'];
    if (isset($offersIblock[$id])) {
        continue;
    }
    $arCatalogIBlock[$id] = '['.$id.'] '.$arr['NAME'];
}
unset($id, $arr, $rsIBlock, $iblockFilter);

$arNewsIBlock = [];
$iblockFilter = !empty($arCurrentValues['NEWS_IBLOCK_TYPE'])
    ? ['TYPE' => $arCurrentValues['NEWS_IBLOCK_TYPE'], 'ACTIVE' => 'Y']
    : ['ACTIVE' => 'Y'];
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch())
{
    $id = (int)$arr['ID'];
    if (isset($offersIblock[$id])) {
        continue;
    }
    $arNewsIBlock[$id] = '['.$id.'] '.$arr['NAME'];
}
unset($id, $arr, $rsIBlock, $iblockFilter);

$arComponentParameters = [
    'PARAMETERS' => [
        'CATALOG_IBLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Тип инфоблока каталога товаров',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockType,
            'REFRESH' => 'Y',
        ],
        'CATALOG_IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Инфоблок каталога товаров',
            'TYPE' => 'LIST',
            'ADDITIONAL_VALUES' => 'Y',
            'VALUES' => $arCatalogIBlock,
            'REFRESH' => 'Y',
        ],
        'CATALOG_SECTION_UF_NEWS_LINK' => [
            'PARENT' => 'BASE',
            'NAME' => 'Имя свойства альтернативного классификатора',
            'TYPE' => 'STRING',
            'DEFAULT' => 'UF_NEWS_LINK',
        ],
        'NEWS_IBLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Тип инфоблока новостей',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockType,
            'REFRESH' => 'Y',
        ],
        'NEWS_IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Инфоблок новостей',
            'TYPE' => 'LIST',
            'ADDITIONAL_VALUES' => 'Y',
            'VALUES' => $arNewsIBlock,
            'REFRESH' => 'Y',
        ],
        'CACHE_TIME' => ['DEFAULT' => 36000000],
    ],
];