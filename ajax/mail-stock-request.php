<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
define("STOP_STATISTICS", true);
CModule::IncludeModule( 'catalog' );
global $APPLICATION;

if(!$_REQUEST['email'])
    die;

$arFields = array(
    "EMAIL" => $_REQUEST['email'],
    "TABLE" => $APPLICATION->ConvertCharset($_REQUEST['table'], "UTF-8", "windows-1251")
);

if(CEvent::Send("MAIL_STOCK_LIST", SITE_ID, $arFields)):
?>
    <div id="in-stock-request">
        <div class="stock-msg-send">
            <h1>������� �� ���������!</h1>
            <p>��������� ������� ����������.</p>
            <p>� ��������� ����� ��� ���������� ������� ���.</p>
        </div>
    </div>
<?endif;?>
