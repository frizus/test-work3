<?php
namespace Frizus\Middle\Helper;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\SystemException;

class IBlockHelper
{
    public static function catalogId()
    {
        return static::getIBlockIdByCode(CATALOG_IBLOCK_CODE);
    }

    public static function getIBlockIdByCode($code)
    {
        static $iblockIdByCode = [];

        if (!isset($iblockIdByCode[$code])) {
            $result = IblockTable::getList([
                'select' => ['ID'],
                'filter' => ['=CODE' => $code],
                'limit' => 1,
            ]);

            $row = $result->fetch();

            if (!$row) {
                throw new SystemException("Не найден инфоблок с кодом $code");
            }

            $iblockIdByCode[$code] = $row['ID'];
        }

        return $iblockIdByCode[$code];
    }
}