<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
if(strlen($arResult["ERROR_MESSAGE"])>0)
	ShowError($arResult["ERROR_MESSAGE"]);
$arPlacemarks = array();
$gpsN = '';
$gpsS = '';
?>

<h3 class="" style="margin: 10px auto;">Самовывоз: бесплатно</h3>

<div class="region-shop">

	<ul class="shop-list">

		<?if(is_array($arResult["STORES"]) && !empty($arResult["STORES"])):
			foreach($arResult["STORES"] as $pid=>$arProperty):?>
				<li class="shop <?=($pid < 1)? "active" : ""?>" data-pid="<?=$pid?>">
					<span class="shop-name"><?=$arProperty["STORE_TITLE"]?></span>
					<span class="shop-address"><?=$arProperty["ADDRESS"]?></span>
					<div class="call-manager">
						Сроки доставки: <a href="#callback2_popup" class="fancy">уточнить у менеджера</a>
					</div>

					<?
					$text = "<div class='store-map' style='margin-top: -10px;margin-bottom: -10px;'>
					<strong>Адрес:</strong><br><span style='font-size: 12px'>$arProperty[ADDRESS]</span>
					<strong>Телефон:</strong><br><span style='font-size: 12px'>$arProperty[PHONE]</span>
					<strong>Режим работы:</strong><br><span style='font-size: 12px'>$arProperty[SCHEDULE]</span>
					<a style='display: block;padding: 10px 12px;font-size: 12px;font-weight: bold;margin: auto 10px;' class='button' href='/catalog/element/?action=BUY&id=$arParams[PRODUCT_ID]'>Добавить в корзину</a>
					</div>";
					if($arProperty["GPS_S"]!=0 && $arProperty["GPS_N"]!=0)
					{
						$gpsN=substr(doubleval($arProperty["GPS_N"]),0,15);
						$gpsS=substr(doubleval($arProperty["GPS_S"]),0,15);
						$arPlacemarks[] = array(
							"LON"=>$gpsS,
							"LAT"=>$gpsN,
							"TEXT"=> $text
						);
					}
					?>
				</li>
			<?endforeach;
		endif;?>
	</ul>

	<div class="shop-map">
		<?
		$APPLICATION->IncludeComponent("bitrix:map.yandex.view", "map.yandex.view", array(
			"INIT_MAP_TYPE" => "MAP",
			"MAP_DATA" => serialize(array("yandex_lat"=>$gpsN,"yandex_lon"=>$gpsS,"yandex_scale"=>14,"PLACEMARKS" => $arPlacemarks)),
			"MAP_WIDTH" => "100%",
			"MAP_HEIGHT" => "380",
			"CONTROLS" => array(
				0 => "ZOOM",
			),
			"OPTIONS" => array(
				0 => "ENABLE_SCROLL_ZOOM",
				1 => "ENABLE_DBLCLICK_ZOOM",
				2 => "ENABLE_DRAGGING",
			),
			"MAP_ID" => ""
		),
			$component,
			array("HIDE_ICONS" => "Y")
		);
		?>
	</div>

	<div class="clear"></div>
</div>


