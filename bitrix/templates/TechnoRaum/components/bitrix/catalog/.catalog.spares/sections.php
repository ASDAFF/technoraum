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
                "",
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
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
        </div>
    </div>