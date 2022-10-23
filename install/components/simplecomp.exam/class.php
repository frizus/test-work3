<?php

use Bitrix\Iblock\Component\Tools;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserFieldTable;
use Bitrix\Sale\Property;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class FrizusSimpleCompExam extends CBitrixComponent
{
    public const ERROR_TEXT = 1;
    public const ERROR_404 = 2;

    protected $useCatalog;

    protected $storage;

    /** @var ErrorCollection */
    protected $errorCollection;

    public function __construct($component = null)
    {
        parent::__construct($component);
        $this->errorCollection = new ErrorCollection();
    }

    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 36000000;
        }

        $arParams['CATALOG_IBLOCK_ID'] = isset($arParams['CATALOG_IBLOCK_ID']) ? (int)$arParams['CATALOG_IBLOCK_ID'] : 0;
        $arParams['CATALOG_SECTION_UF_NEWS_LINK'] = isset($arParams['CATALOG_SECTION_UF_NEWS_LINK']) ? trim($arParams['CATALOG_SECTION_UF_NEWS_LINK']) : '';
        $arParams['NEWS_IBLOCK_ID'] = isset($arParams['NEWS_IBLOCK_ID']) ? (int)$arParams['NEWS_IBLOCK_ID'] : 0;

        return $arParams;
    }

    public function executeComponent()
    {
        if ($this->startResultCache()) {
            $this->checkModules();

            if ($this->hasErrors()) {
                $this->abortResultCache();
                return $this->processErrors();
            }

            $this->prepareResult();

            if ($this->hasErrors()) {
                $this->abortResultCache();
                return $this->processErrors();
            }

            $this->includeComponentTemplate();
        }

        /** @var \CMain $APPLICATION */
        global $APPLICATION;
        $APPLICATION->SetPageProperty('title', $this->arResult['TITLE']);
    }

    protected function prepareResult()
    {
        $newsLink = $this->arParams['CATALOG_SECTION_UF_NEWS_LINK'];

        if ($newsLink === '') {
            $this->errorCollection->setError(new Error('Не указано имя свойства альтернативного классификатора', self::ERROR_TEXT));
            return;
        }

        $result = ElementTable::getList([
            'select' => ['ID', 'NAME', 'ACTIVE_FROM'],
            'filter' => [
                '=ACTIVE' => 'Y',
                '=IBLOCK_ID' => $this->arParams['NEWS_IBLOCK_ID'],
            ],
        ]);

        $newsIds = [];
        $this->arResult['GROUPS'] = [];
        while ($row = $result->fetch()) {
            $newsIds[] = $row['ID'];
            $this->arResult['GROUPS'][$row['ID']] = $row;
        }

        if (empty($newsIds)) {
            return;
        }

        $result = CIBlockSection::GetList([], [
            'IBLOCK_ID' => $this->arParams['CATALOG_IBLOCK_ID'],
            'ACTIVE' => 'Y',
            '@' . $newsLink => $newsIds,
        ], false, [
            'ID',
            'NAME',
            'IBLOCK_ID',
            $newsLink
        ], false);

        $sections = [];
        while ($row = $result->Fetch()) {
            if (is_array($row[$newsLink]) && !empty($row[$newsLink])) {
                $sections[$row['ID']] = [];

                foreach ($row[$newsLink] as $newsId) {
                    if (array_key_exists($newsId, $this->arResult['GROUPS'])) {
                        $this->arResult['GROUPS'][$newsId]['CATALOG_SECTIONS'][$row['ID']] = $row;
                        $sections[$row['ID']][] = $newsId;
                    }
                }
            }
        }

        $count = 0;

        if (!empty($sections)) {
            $result = CIBlockElement::GetList([], [
                'IBLOCK_ID' => $this->arParams['CATALOG_IBLOCK_ID'],
                '@SECTION_ID' => array_keys($sections),
            ], false, false, [
                'ID',
                'NAME',
                'IBLOCK_ID',
                'PROPERTIES',
            ]);

            while ($row = $result->GetNextElement(true, false)) {
                $element = $row->GetFields();
                $element['PROPERTIES'] = $row->GetProperties();
                $result2 = CIBlockElement::GetElementGroups($element['ID'], true);
                while ($row2 = $result2->Fetch()) {
                    foreach ($sections[$row2['ID']] as $newsId) {
                        $this->arResult['GROUPS'][$newsId]['PRODUCTS'][$element['ID']] = &$element;
                    }
                }
                unset($element);
                $count++;
            }
        }

        if ($count === 0) {
            $this->arResult['GROUPS'] = [];
        }

        $this->setResultCacheKeys([
            'TITLE'
        ]);
        $this->arResult['TITLE'] = 'В каталоге товаров представлено товаров: ' . $count;
    }

    protected function checkModules()
    {
        if (!Loader::includeModule('iblock')) {
            $this->errorCollection->setError(new Error('Не удалось загрузить модуль iblock', self::ERROR_TEXT));
            return;
        }
    }

    protected function hasErrors()
    {
        return (bool)count($this->errorCollection);
    }

    protected function processErrors()
    {
        if (!empty($this->errorCollection))
        {
            /** @var Error $error */
            foreach ($this->errorCollection as $error)
            {
                $code = $error->getCode();

                if ($code == self::ERROR_404)
                {
                    Tools::process404(
                        trim($this->arParams['MESSAGE_404']) ?: $error->getMessage(),
                        true,
                        $this->arParams['SET_STATUS_404'] === 'Y',
                        $this->arParams['SHOW_404'] === 'Y',
                        $this->arParams['FILE_404']
                    );
                }
                elseif ($code == self::ERROR_TEXT)
                {
                    ShowError($error->getMessage());
                }
            }
        }

        return false;
    }
}