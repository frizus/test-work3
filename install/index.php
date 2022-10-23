<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;
use Frizus\Middle\Handler\NewContentManager;
use Frizus\Middle\Handler\PreventDeactivationOfPopularProducts;

class frizus_middle extends CModule
{
    public function __construct()
    {
        $arModuleVersion = null;
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_ID = 'frizus.middle';
        $this->MODULE_NAME = 'Компонент "Каталог товаров", почтовый шаблон и запрет удаления';
        $this->MODULE_DESCRIPTION = "Для работы требуется модуль https://github.com/andreyryabin/sprint.migration\nПосле установки требуется установить миграции в Настройки -> Миграции для разработчиков -> Миграции (cfg)";;
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = 'frizus';
        $this->PARTNER_URI = '';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandlerCompatible('iblock', 'OnBeforeIBlockElementUpdate', $this->MODULE_ID, PreventDeactivationOfPopularProducts::class, 'OnBeforeIBlockElementUpdate');
        $eventManager->registerEventHandlerCompatible('main', 'OnBeforeUserAdd', $this->MODULE_ID, NewContentManager::class, 'OnBeforeUserAdd');
        $eventManager->registerEventHandlerCompatible('main', 'OnBeforeUserUpdate', $this->MODULE_ID, NewContentManager::class, 'OnBeforeUserUpdate');
        $eventManager->registerEventHandlerCompatible('main', 'OnAfterUserAdd', $this->MODULE_ID, NewContentManager::class, 'OnAfterUserAdd');
        $eventManager->registerEventHandlerCompatible('main', 'OnAfterUserUpdate', $this->MODULE_ID, NewContentManager::class, 'OnAfterUserUpdate');
        $eventManager->registerEventHandlerCompatible('main', 'OnBeforeGroupUpdate', $this->MODULE_ID, NewContentManager::class, 'OnBeforeGroupUpdate');
        $eventManager->registerEventHandlerCompatible('main', 'OnAfterGroupUpdate', $this->MODULE_ID, NewContentManager::class, 'OnAfterGroupUpdate');

        CopyDirFiles(__DIR__ . '/components/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/' . $this->MODULE_ID . '/', true, true);
        CopyDirFiles(__DIR__ . '/migrations/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/migrations/', true, true);
    }

    public function doUninstall()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler('iblock', 'OnBeforeIBlockElementUpdate', $this->MODULE_ID, PreventDeactivationOfPopularProducts::class, 'OnBeforeIBlockElementUpdate');
        $eventManager->unRegisterEventHandler('main', 'OnBeforeUserAdd', $this->MODULE_ID, NewContentManager::class, 'OnBeforeUserAdd');
        $eventManager->unRegisterEventHandler('main', 'OnBeforeUserUpdate', $this->MODULE_ID, NewContentManager::class, 'OnBeforeUserUpdate');
        $eventManager->unRegisterEventHandler('main', 'OnAfterUserAdd', $this->MODULE_ID, NewContentManager::class, 'OnAfterUserAdd');
        $eventManager->unRegisterEventHandler('main', 'OnAfterUserUpdate', $this->MODULE_ID, NewContentManager::class, 'OnAfterUserUpdate');
        $eventManager->unRegisterEventHandler('main', 'OnBeforeGroupUpdate', $this->MODULE_ID, NewContentManager::class, 'OnBeforeGroupUpdate');
        $eventManager->unRegisterEventHandler('main', 'OnAfterGroupUpdate', $this->MODULE_ID, NewContentManager::class, 'OnAfterGroupUpdate');

        DeleteDirFiles(__DIR__ . '/components/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/' . $this->MODULE_ID . '/');
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}