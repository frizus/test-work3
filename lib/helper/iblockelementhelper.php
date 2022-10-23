<?php
namespace Frizus\Middle\Helper;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\SystemException;

class IBlockElementHelper
{
    public static function getElement($id)
    {
        $result = ElementTable::getList([
            'select' => ['ID', 'ACTIVE', 'IBLOCK_ID', 'SHOW_COUNTER'],
            'filter' => ['=ID' => $id],
            'limit' => 1,
        ]);

        $row = $result->fetch();

        if (!$row) {
            throw new SystemException("Элемент с id $id не найден.");
        }

        return $row;
    }
}