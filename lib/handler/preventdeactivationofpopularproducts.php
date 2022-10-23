<?php
namespace Frizus\Middle\Handler;

use Bitrix\Main\SystemException;
use Frizus\Middle\Helper\IBlockElementHelper;
use Frizus\Middle\Helper\IBlockHelper;

class PreventDeactivationOfPopularProducts
{
    protected static $element;

    public static function OnBeforeIBlockElementUpdate(&$arFields)
    {
        if (!array_key_exists('ACTIVE', $arFields) || ($arFields['ACTIVE'] !== 'N')) {
            return;
        }

        try {
            $iblockId = self::getIBlockId($arFields);

            if ($iblockId === IBlockHelper::catalogId()) {
                $row = self::$element ?? IBlockElementHelper::getElement($arFields['ID']);

                if (isset($row['SHOW_COUNTER']) &&
                    ($row['SHOW_COUNTER'] !== '') &&
                    (intval($row['SHOW_COUNTER']) >= POPULAR_PRODUCT_VIEWS_THRESHOLD)
                ) {
                    throw new SystemException('Товар невозможно деактивировать, у него ' . $row['SHOW_COUNTER'] . ' просмотр(а,ов).');
                }
            }
        } catch (SystemException $e) {
            /** @var \CMain $APPLICATION */
            global $APPLICATION;
            $APPLICATION->ThrowException($e->getMessage());
            return false;
        } finally {
            if (isset(self::$element)) {
                self::$element = null;
            }
        }
    }

    protected static function getIBlockId($arFields)
    {
        if (array_key_exists('IBLOCK_ID', $arFields) && ($arFields['IBLOCK_ID'] !== '')) {
            return strval($arFields['IBLOCK_ID']);
        }

        self::$element = IBlockElementHelper::getElement($arFields['ID']);
        return self::$element['IBLOCK_ID'];
    }
}