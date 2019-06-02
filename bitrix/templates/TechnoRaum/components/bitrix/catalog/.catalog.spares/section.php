<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);
?>

    <div class="row">
        <div class="col-md-12">
            <?$APPLICATION->IncludeComponent("bitrix:catalog.search", ".spares.search", Array(
                "ACTION_VARIABLE" => "action",	// �������� ����������, � ������� ���������� ��������
                "AJAX_MODE" => "N",	// �������� ����� AJAX
                "AJAX_OPTION_ADDITIONAL" => "",	// �������������� �������������
                "AJAX_OPTION_HISTORY" => "N",	// �������� �������� ��������� ��������
                "AJAX_OPTION_JUMP" => "N",	// �������� ��������� � ������ ����������
                "AJAX_OPTION_STYLE" => "Y",	// �������� ��������� ������
                "BASKET_URL" => "/personal/basket.php",	// URL, ������� �� �������� � �������� ����������
                "CACHE_TIME" => "36000000",	// ����� ����������� (���.)
                "CACHE_TYPE" => "A",	// ��� �����������
                "CHECK_DATES" => "N",	// ������ ������ � �������� �� ���� ����������
                "CONVERT_CURRENCY" => "N",	// ���������� ���� � ����� ������
                "DETAIL_URL" => "",	// URL, ������� �� �������� � ���������� �������� �������
                "DISPLAY_BOTTOM_PAGER" => "Y",	// �������� ��� �������
                "DISPLAY_COMPARE" => "N",	// �������� ������ ���������
                "DISPLAY_TOP_PAGER" => "N",	// �������� ��� �������
                "ELEMENT_SORT_FIELD" => "sort",	// �� ������ ���� ��������� ��������
                "ELEMENT_SORT_FIELD2" => "id",	// ���� ��� ������ ���������� ���������
                "ELEMENT_SORT_ORDER" => "asc",	// ������� ���������� ���������
                "ELEMENT_SORT_ORDER2" => "desc",	// ������� ������ ���������� ���������
                "HIDE_NOT_AVAILABLE" => "N",	// ����������� ������
                "HIDE_NOT_AVAILABLE_OFFERS" => "N",	// ����������� �������� �����������
                "IBLOCK_ID" => "16",	// ��������
                "IBLOCK_TYPE" => "catalog",	// ��� ���������
                "LINE_ELEMENT_COUNT" => "3",	// ���������� ��������� ��������� � ����� ������ �������
                "NO_WORD_LOGIC" => "N",	// ��������� ��������� ���� ��� ���������� ����������
                "OFFERS_LIMIT" => "5",	// ������������ ���������� ����������� ��� ������ (0 - ���)
                "PAGER_DESC_NUMBERING" => "N",	// ������������ �������� ���������
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// ����� ����������� ������� ��� �������� ���������
                "PAGER_SHOW_ALL" => "N",	// ���������� ������ "���"
                "PAGER_SHOW_ALWAYS" => "N",	// �������� ������
                "PAGER_TEMPLATE" => ".default",	// ������ ������������ ���������
                "PAGER_TITLE" => "������",	// �������� ���������
                "PAGE_ELEMENT_COUNT" => "30",	// ���������� ��������� �� ��������
                "PRICE_CODE" => array(	// ��� ����
                    0 => "price",
                ),
                "PRICE_VAT_INCLUDE" => "Y",	// �������� ��� � ����
                "PRODUCT_ID_VARIABLE" => "id",	// �������� ����������, � ������� ���������� ��� ������ ��� �������
                "PRODUCT_PROPERTIES" => "",	// �������������� ������
                "PRODUCT_PROPS_VARIABLE" => "prop",	// �������� ����������, � ������� ���������� �������������� ������
                "PRODUCT_QUANTITY_VARIABLE" => "quantity",	// �������� ����������, � ������� ���������� ���������� ������
                "PROPERTY_CODE" => array(	// ��������
                    0 => "",
                    1 => "",
                ),
                "RESTART" => "N",	// ������ ��� ����� ���������� (��� ���������� ���������� ������)
                "SECTION_ID_VARIABLE" => "SECTION_ID",	// �������� ����������, � ������� ���������� ��� ������
                "SECTION_URL" => "",	// URL, ������� �� �������� � ���������� �������
                "SHOW_PRICE_COUNT" => "1",	// �������� ���� ��� ����������
                "USE_LANGUAGE_GUESS" => "Y",	// �������� ��������������� ��������� ����������
                "USE_PRICE_COUNT" => "N",	// ������������ ����� ��� � �����������
                "USE_PRODUCT_QUANTITY" => "N",	// ��������� �������� ���������� ������
            ),
                false
            );?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">

            <?$APPLICATION->IncludeComponent("bitrix:menu", ".menu.spares", Array(
                "ALLOW_MULTI_SELECT" => "N",	// ��������� ��������� �������� ������� ������������
                "CHILD_MENU_TYPE" => "",	// ��� ���� ��� ��������� �������
                "DELAY" => "N",	// ����������� ���������� ������� ����
                "MAX_LEVEL" => "1",	// ������� ����������� ����
                "MENU_CACHE_GET_VARS" => array(	// �������� ���������� �������
                    0 => "",
                ),
                "MENU_CACHE_TIME" => "3600",	// ����� ����������� (���.)
                "MENU_CACHE_TYPE" => "N",	// ��� �����������
                "MENU_CACHE_USE_GROUPS" => "Y",	// ��������� ����� �������
                "ROOT_MENU_TYPE" => "spares",	// ��� ���� ��� ������� ������
                "USE_EXT" => "N",	// ���������� ����� � ������� ���� .���_����.menu_ext.php
            ),
                false
            );?>

        </div>
        <div class="col-md-8">
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.section.list",
                ".table",
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                    "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                    "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                    "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                    "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
                ),
                $component,
                array("HIDE_ICONS" => "Y")
            );
            ?>

            <?

            if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
            {
                $basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '');
            }
            else
            {
                $basketAction = (isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '');
            }
            $intSectionID = 0;
            ?>

            <?$intSectionID = $APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                "",
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                    "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                    "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                    "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                    "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                    "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
                    "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
                    "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
                    "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                    "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                    "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "MESSAGE_404" => $arParams["MESSAGE_404"],
                    "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                    "SHOW_404" => $arParams["SHOW_404"],
                    "FILE_404" => $arParams["FILE_404"],
                    "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                    "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
                    "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                    "PRICE_CODE" => $arParams["PRICE_CODE"],
                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                    "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                    "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                    "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                    "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

                    "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                    "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                    "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                    "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                    "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                    "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                    "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                    "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                    "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
                    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],

                    "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                    "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                    "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
                    "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                    "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                    "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                    "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                    "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

                    "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                    "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

                    'LABEL_PROP' => $arParams['LABEL_PROP'],
                    'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

                    'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                    'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                    'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
                    'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
                    'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
                    'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
                    'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],

                    'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                    "ADD_SECTIONS_CHAIN" => "N",
                    'ADD_TO_BASKET_ACTION' => $basketAction,
                    'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                    'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
                    'SECTION_USER_FIELDS' => array('UF_DETAIL_SPARES')
                ),
                $component
            );?>

            <?
            $GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;
            unset($basketAction);

            ?>
        </div>
    </div>



