<?php
namespace Frizus\Middle\Helper;

use Bitrix\Main\Application;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\UserTable;

class UserHelper
{
    /**
     * @see UserTable::getUserGroupIds()
     */
    public static function getContentManagers()
    {
        $result = UserTable::getList([
            'select' => ['ID'],
            'filter' => [
                '=GROUPS.GROUP_ID' => GroupHelper::contentManagersId(),
            ],
        ]);

        $rows = [];

        while ($row = $result->fetch()) {
            $rows[$row['ID']] = $row;
        }

        return $rows;
    }

    public static function getUsers($ids)
    {
        if (empty($ids)) {
            return [];
        }

        $result = UserTable::getList([
            'select' => ['ID', 'NAME', 'LAST_NAME', 'LOGIN', 'EMAIL'],
            'filter' => [
                '@ID' => $ids,
            ]
        ]);

        $rows = [];
        while ($row = $result->fetch()) {
            $rows[$row['ID']] = $row;
        }

        return $rows;
    }
}