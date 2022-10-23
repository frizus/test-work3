<?php

namespace Sprint\Migration;


class VersionMessageNewContentManager20221023162006 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Event()->saveEventType('NEW_CONTENT_MANAGER', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Новый контент-редактор',
  'DESCRIPTION' => '#EMAIL_TO# - E-Mail получателя
#USER_ID# - ID контент-редактора
#USER_NAME# - имя контент-редактора
#USER_EMAIL# - E-Mail контент-редактора',
  'SORT' => '150',
));
            $helper->Event()->saveEventType('NEW_CONTENT_MANAGER', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Новый контент-редактор',
  'DESCRIPTION' => '#EMAIL_TO# - E-Mail получателя
#USER_ID# - ID контент-редактора
#USER_NAME# - имя контент-редактора
#USER_EMAIL# - E-Mail контент-редактора',
  'SORT' => '150',
));
            $helper->Event()->saveEventMessage('NEW_CONTENT_MANAGER', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
  'EMAIL_TO' => '#EMAIL_TO#',
  'SUBJECT' => '#SITE_NAME#: Новый контент-редактор #USER_NAME#',
  'MESSAGE' => 'Информационное сообщение сайта #SITE_NAME#
------------------------------------------

На сайте появился новый контент-редактор #USER_NAME# (#USER_EMAIL#).

Сообщение сгенерировано автоматически.',
  'BODY_TYPE' => 'text',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => '',
  'IN_REPLY_TO' => '',
  'PRIORITY' => '',
  'FIELD1_NAME' => '',
  'FIELD1_VALUE' => '',
  'FIELD2_NAME' => '',
  'FIELD2_VALUE' => '',
  'SITE_TEMPLATE_ID' => '',
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => 'ru',
  'EVENT_TYPE' => '[ NEW_CONTENT_MANAGER ] Новый контент-редактор',
));
        }

    public function down()
    {
        //your code ...
    }
}
