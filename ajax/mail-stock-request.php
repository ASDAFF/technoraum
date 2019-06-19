<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
define("STOP_STATISTICS", true);
CModule::IncludeModule( 'catalog' );

if(!$_REQUEST['email'])
    die;

$arFields = array(
    "EMAIL" => $_REQUEST['email'],
    "TABLE" => $_REQUEST['table']
);

if(CEvent::Send("MAIL_STOCK_LIST", SITE_ID, $arFields)):
?>
    <div id="in-stock-request">
        <div class="stock-msg-send">
            <h1>Спасибо за обращение!</h1>
            <p>Сообщение успешно отправлено.</p>
            <p>В ближайшее время наш специалист ответит вам.</p>
        </div>
    </div>
<?endif;?>
