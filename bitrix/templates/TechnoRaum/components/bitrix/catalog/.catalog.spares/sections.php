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