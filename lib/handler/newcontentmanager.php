<?php
namespace Frizus\Middle\Handler;

use Bitrix\Main\SystemException;
use Frizus\Middle\Helper\GroupHelper;
use Frizus\Middle\Helper\UserHelper;

/**
 * TODO добавить проверку на активность группы Контент-редакторы и период активности Контент-редакторов
 */
class NewContentManager
{
    protected static $previousContentManagers;

    protected static $savingUserId;

    protected static $savingGroupId;

    public static function OnBeforeUserAdd(&$arFields)
    {
        return self::OnBeforeUserSave(null, $arFields);
    }

    public static function OnBeforeUserUpdate(&$arFields)
    {
        return self::OnBeforeUserSave($arFields['ID'], $arFields);
    }

    /**
     * @see \CUser::Add()
     * @see \CUser::Update()
     */
    protected static function OnBeforeUserSave($ID, &$arFields)
    {
        if (!is_set($arFields, 'GROUP_ID')) {
            return;
        }

        try {
            if (is_array($arFields['GROUP_ID'])) {
                try {
                    $contentManagersId = GroupHelper::contentManagersId();
                } catch (SystemException $e) {
                    return;
                }

                $isContentManager = false;
                foreach ($arFields['GROUP_ID'] as $group) {
                    if (strval($group['GROUP_ID']) === $contentManagersId) {
                        $isContentManager = true;
                        break;
                    }
                }

                if ($isContentManager) {
                    $previousContentManagers = UserHelper::getContentManagers();

                    if (!empty($previousContentManagers)) {
                        if (isset($ID)) {
                            $isNewContentManager = !array_key_exists(strval($ID), $previousContentManagers);
                        } else {
                            $isNewContentManager = true;
                        }

                        if ($isNewContentManager) {
                            self::$savingUserId = isset($ID) ? strval($ID) : 'add';
                            self::$previousContentManagers = $previousContentManagers;
                        }
                    }
                }
            }
        } catch (SystemException $e) {
            /** @var \CMain $APPLICATION */
            global $APPLICATION;
            $APPLICATION->ThrowException($e->getMessage());
            return false;
        }
    }

    public static function OnAfterUserAdd(&$arFields)
    {
        if (!($arFields['ID'] > 0) || (self::$savingUserId !== 'add')) {
            return;
        }

        self::OnAfterUserSave($arFields['ID'], $arFields);
    }

    public static function OnAfterUserUpdate(&$arFields)
    {
        if (!$arFields['RESULT'] || (self::$savingUserId !== strval($arFields['ID']))) {
            return;
        }

        self::OnAfterUserSave($arFields['ID'], $arFields);
    }

    protected static function OnAfterUserSave($ID, &$arFields)
    {
        static::notifyOfNewContentManagers(array_keys(self::$previousContentManagers), [strval($ID)]);
        self::$previousContentManagers = null;
    }

    /**
     * @see \CGroup::Update()
     */
    public static function OnBeforeGroupUpdate($ID, &$arFields)
    {
        if (!(is_set($arFields, 'USER_ID') && is_array($arFields['USER_ID']))) {
            return;
        }

        try {
            try {
                $contentManagersId = GroupHelper::contentManagersId();
            } catch (SystemException $e) {
                return;
            }
            $ID = strval($ID);
            if ($ID === $contentManagersId) {
                $previousContentManagers = UserHelper::getContentManagers();

                if (!empty($previousContentManagers)) {
                    self::$savingGroupId = $ID;
                    self::$previousContentManagers = $previousContentManagers;
                }
            }
        } catch (SystemException $e) {
            /** @var \CMain $APPLICATION */
            global $APPLICATION;
            $APPLICATION->ThrowException($e->getMessage());
            return false;
        }
    }

    public static function OnAfterGroupUpdate($ID, &$arFields)
    {
        if (self::$savingGroupId !== strval($ID)) {
            return;
        }

        try {
            $currentContentManagers = UserHelper::getContentManagers();
            $newContentManagers = array_diff_key($currentContentManagers, self::$previousContentManagers);

            if (!empty($newContentManagers)) {
                $stayedContentManagers = array_intersect_key(self::$previousContentManagers, $currentContentManagers);

                if (!empty($stayedContentManagers)) {
                    static::notifyOfNewContentManagers(array_keys($stayedContentManagers), array_keys($newContentManagers));
                }
            }
        } catch (SystemException $e) {
            /** @var \CMain $APPLICATION */
            global $APPLICATION;
            $APPLICATION->ThrowException($e->getMessage());
        } finally {
            self::$savingGroupId = null;
            self::$previousContentManagers = null;
        }
    }

    protected static function notifyOfNewContentManagers($oldContentManagers, $newContentManagers)
    {
        $rows = UserHelper::getUsers(array_merge($oldContentManagers, $newContentManagers));

        foreach ($newContentManagers as $newContentManager) {
            //NEW_CONTENT_MANAGER
            //#EMAIL_TO# - E-Mail получателя
            //#USER_ID# - ID контент-редактора
            //#USER_NAME# - имя контент-редактора
            //#USER_EMAIL# - E-Mail контент-редактора

            $row = $rows[$newContentManager];
            $arEventFields = [
                'USER_ID' => $newContentManager,
                'USER_NAME' => self::getName($row),
                'USER_EMAIL' => $row['EMAIL'],
            ];

            foreach ($oldContentManagers as $oldContentManager) {
                $row2 = $rows[$oldContentManager];

                if (isset($row2['EMAIL']) && ($row2['EMAIL'] !== '')) {
                    $arEventFields['EMAIL_TO'] = $row2['EMAIL'];

                    \CEvent::SendImmediate('NEW_CONTENT_MANAGER', FRIZUS_SITE_ID, $arEventFields);
                }
            }
        }
    }

    protected static function getName($row)
    {
        $userName = '';
        $haveName = isset($row['NAME']) && ($row['NAME'] !== '');
        if ($haveName) {
            $userName = $row['NAME'];
        }
        $haveLastName = isset($row['LAST_NAME']) && ($row['LAST_NAME'] !== '');
        if ($haveLastName) {
            if ($haveName) {
                $userName .= ' ';
            }
            $userName .= $row['LAST_NAME'];
        }
        if ($haveName || $haveLastName) {
            $userName .= ' (' . $row['LOGIN'] . ')';
        } else {
            $userName = $row['LOGIN'];
        }

        return $userName;
    }
}