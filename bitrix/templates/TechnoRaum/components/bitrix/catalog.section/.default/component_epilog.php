<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

global $APPLICATION;

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateFolder.'/themes/'.$templateData['TEMPLATE_THEME'].'/style.css');
	$APPLICATION->SetAdditionalCSS('/bitrix/css/main/themes/'.$templateData['TEMPLATE_THEME'].'/style.css', true);
}

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = \Bitrix\Main\Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);

	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

//	lazy load and big data json answers
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if ($request->isAjaxRequest() && ($request->get('action') === 'showMore' || $request->get('action') === 'deferredLoad'))
{
	$content = ob_get_contents();
	ob_end_clean();

	list(, $itemsContainer) = explode('<!-- items-container -->', $content);
	list(, $paginationContainer) = explode('<!-- pagination-container -->', $content);

	if ($arParams['AJAX_MODE'] === 'Y')
	{
		$component->prepareLinks($paginationContainer);
	}

	$component::sendJsonAnswer(array(
		'items' => $itemsContainer,
		'pagination' => $paginationContainer
	));
}

$meta_array = function($arMeta){
	$arMetas = array();
	foreach($arMeta as $meta){
		$metas = explode(";",$meta);
		$arMetas[$metas[0]] = $metas[1];
	}
	return $arMetas;
};

$arTitle = $meta_array($arResult["META_TITLE"]);
if (isset($arTitle[SITE_ID])) {
	$APPLICATION->SetTitle($arTitle[SITE_ID]);
	$APPLICATION->SetPageProperty('title', $arTitle[SITE_ID]);
}else{
	$APPLICATION->SetTitle($arResult["IPROPERTY_VALUES"]["SECTION_META_TITLE"]);
	$APPLICATION->SetPageProperty('title', $arResult["IPROPERTY_VALUES"]["SECTION_META_TITLE"]);
}

$arKeywords = $meta_array($arResult["META_KEYWORDS"]);
if (isset($arKeywords[SITE_ID])) {
	$APPLICATION->SetPageProperty('keywords', $arKeywords[SITE_ID]);
}else{
	$APPLICATION->SetPageProperty('keywords', $arResult["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"]);
}

$arDescription = $meta_array($arResult["META_DESCRIPTION"]);
if (isset($arDescription[SITE_ID])) {
	$APPLICATION->SetPageProperty('description', $arDescription[SITE_ID]);
}else{
	$APPLICATION->SetPageProperty('description', $arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]);
}
