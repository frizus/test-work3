# Установка

1. Скопировать репозиторий в папку проекта `frizus.middle` в папку проекта `/local/modules/`
2. Установить модуль [sprint.migration](https://github.com/andreyryabin/sprint.migration)
3. Установить модуль `frizus.reviews`, `spint.migration`
4. Установить миграции `Настройки -> Миграции для разработчиков -> Миграции (cfg)`
   1. Теряются привязки разделов каталога товаров к новостям, так как id'ы меняются, нужно их заново проставить (использовано наполнение как в примере)
5. Создать файл `/ex2/simplecomp/index.php` с содержимым:
```php
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
use Bitrix\Main\Loader;
use Frizus\Middle\Helper\IBlockHelper;
Loader::includeModule('frizus.middle');
Loader::includeModule('iblock');

$APPLICATION->IncludeComponent(
	"frizus.middle:simplecomp.exam",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CATALOG_IBLOCK_ID" => IBlockHelper::catalogId(),
		"CATALOG_IBLOCK_TYPE" => "catalog_and_news",
		"CATALOG_SECTION_UF_NEWS_LINK" => "UF_NEWS_LINK",
		"NEWS_IBLOCK_ID" => IBlockHelper::getIBlockIdByCode('news'),
		"NEWS_IBLOCK_TYPE" => "catalog_and_news"
	)
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
```
6. Тестировать
   1. Изменить в БД `SHOW_COUNTER` на значение больше 2 у товара и проверить деактивацию товара
   2. Добавить пользователя в группу пользователей `Контент-редакторы`, добавить второго. Проверить отправленные письма
   3. Зайти на страницу `/ex2/simplecomp/`, проверить работу компонента

#

Тестировал на последней версии Битрикс.
