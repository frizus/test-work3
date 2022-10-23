<?php
namespace Frizus\Middle\Helper;

use Bitrix\Main\GroupTable;
use Bitrix\Main\SystemException;

class GroupHelper
{
    public static function contentManagersId()
    {
        return static::getGroupIdByCode(CONTENT_MANAGERS_GROUP_CODE);
    }

    public static function getGroupIdByCode($code)
    {
        static $groupByCode = [];

        if (!isset($groupByCode[$code])) {
            $result = GroupTable::getList([
                'select' => ['ID'],
                'filter' => ['=STRING_ID' => $code],
                'limit' => 1,
            ]);

            $row = $result->fetch();

            if (!$row) {
                throw new SystemException("Не найдена группа пользователей с кодом $code");
            }

            $groupByCode[$code] = $row['ID'];
        }

        return $groupByCode[$code];
    }

    public static function getGroup($id)
    {
        $result = GroupTable::getList([
            'select' => ['ID', 'STRING_ID'],
            'filter' => ['=ID' => $id],
            'limit' => 1,
        ]);

        $row = $result->fetch();

        if (!$row) {
            throw new SystemException("Группа пользователей с id $id не найдена.");
        }

        return $row;
    }
}