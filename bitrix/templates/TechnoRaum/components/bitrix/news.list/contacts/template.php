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

//combobox
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/combobox.js");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/contact-tabs.js");
?>
<script src="https://api-maps.yandex.ru/2.1/?load=package.full&mode=release&lang=ru-RU&wizard=bitrix" type="text/javascript"></script>

<div id="map-data" data-items="<?=$arResult["ITEMS_JS"]?>"></div>

<?if(count($arResult['CITYS']) > 0):?>
<div class="ui-city-select">
	<label>Город: </label>
	<select id="combobox">
		<?foreach($arResult['CITYS'] as $city):?>
		<option value="<?=$city['XML_ID']?>" <?=($arResult["CURRENT_CITY_CODE"] == $city['XML_ID']) ? "selected" : ""?>><?=$city['VALUE']?></option>
		<?endforeach;?>
	</select>
</div>
<?endif;?>

<div id="tabs-contact">
	<ul>
		<li><a href="#shop">Магазины</a></li>
		<li><a href="#service">Сервисные центры</a></li>
		<li><a href="#sdek">Пункты выдачи СДЭК</a></li>
	</ul>
	<div class="tab-line"></div>
	<div id="shop">

		<div class="region-shop">

			<ul class="shop-list"></ul>

            <div class="shop-map">
                <div id="shop_map"></div>
            </div>

			<div class="clear"></div>
		</div>

	</div>
	<div id="service">

		<div class="region-shop">

			<ul class="shop-list"></ul>

            <div class="shop-map">
                <div id="service_map"></div>
            </div>

			<div class="clear"></div>
		</div>

	</div>
    <div id="sdek">
        <?$APPLICATION->IncludeComponent(
            "ipol:ipol.sdekPickup",
            "",
            Array(
                "CITIES" => array(),
                "CNT_BASKET" => "N",
                "CNT_DELIV" => "Y",
                "COUNTRIES" => array(),
                "FORBIDDEN" => array("courier"),
                "NOMAPS" => "Y",
                "PAYER" => "",
                "PAYSYSTEM" => ""
            )
        );?>
    </div>

</div>
<!--TABS-END-->

<script type="html/tpl" id="item-temp">

	<li class="shop" data-pid="{pid}" data-cord="{cord}">
		<div class="shop-name">{name}</div>
		<div class="shop-body">
			<span><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:79183257002">{phone}</a></span>
			<span><i class="fa fa-clock-o" aria-hidden="true"></i> {mode}</span>
			<span><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Ежедневно без перерывов и выходных</span>
		</div>
	</li>

</script>
