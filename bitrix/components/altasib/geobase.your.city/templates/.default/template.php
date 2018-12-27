<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @var $arResult array
 * @var $arParams array
 * @var $APPLICATION CMain
 * @var $USER CUser
 * @var $component CBitrixComponent
 * @var $this CBitrixComponentTemplate
 */

$this->setFrameMode(true);
$frame = $this->createFrame()->begin("");

$shortName = "";
$fullName = "";
$sCity = "";
$sRegion = "";
$sCoutnry = "";
$sMode = $arResult['MODE_LOCATION'];

if(!empty($arResult["CITY"]["NAME"]))
{
	$sCity = $shortName = $arResult["CITY"]["NAME"];
	$fullName = $arResult["CITY"]["SOCR"].'. '.$arResult["CITY"]["NAME"].', '.$arResult["REGION"]["FULL_NAME"]
		.(!empty($arResult['DISTRICT']['SOCR']) ? ', '.$arResult['DISTRICT']['NAME'].' '.$arResult['DISTRICT']['SOCR'].'.' : '');
	if($arResult["REGION_DISABLE"] != "Y"){
		$sRegion = ' ('.$arResult["REGION"]["FULL_NAME"]
		.((isset($arResult["DISTRICT"]["NAME"]) && $arResult["DISTRICT"]["NAME"]!='') ? ', '.$arResult['DISTRICT']['NAME'].' '.$arResult['DISTRICT']['SOCR'].'.' : '').')';
	}
}
elseif(!empty($arResult["REGION"]["NAME"]))
{
	$shortName = $arResult["REGION"]["NAME"];
	if(!empty($arResult["REGION"]["SOCR"]) && $arResult["REGION"]["SOCR"] != GetMessage('ALTASIB_GEOBASE_G'))
		$shortName .= " ".$arResult["REGION"]["SOCR"];

	$sCity = $fullName = $arResult["REGION"]["FULL_NAME"];
}
elseif(isset($arResult["auto"]["CITY_NAME"]))
{
	$sCity = $shortName = $arResult["auto"]["CITY_NAME"];
	$fullName = $arResult["auto"]["CITY_NAME"]
		.(!empty($arResult['auto']['REGION_NAME']) ? ', '.$arResult['auto']['REGION_NAME'] : '')
		.(!empty($arResult['auto']['COUNTRY_NAME']) ? ' ('.$arResult['auto']['COUNTRY_NAME'].')' : '');

	if($arResult["REGION_DISABLE"] != "Y" && !empty($arResult["auto"]["REGION_NAME"]))
	{
		$sRegion = ' ('.$arResult["auto"]["REGION_NAME"].')';
	}
	if(!empty($arResult["auto"]["COUNTRY_NAME"]))
	{
		$sCoutnry = ', '.$arResult["auto"]["COUNTRY_NAME"];
	}
}
?>

<script language="JavaScript">
if(typeof altasib_geobase=="undefined")
	var altasib_geobase={};
altasib_geobase.short_name='<?=$shortName;?>';
altasib_geobase.full_name='<?=$fullName;?>';
altasib_geobase.is_mobile=false;
altasib_geobase.COOKIE_PREFIX='<?=COption::GetOptionString("main", "cookie_name", "BITRIX_SM");?>';
altasib_geobase.bitrix_sessid='<?=bitrix_sessid();?>';
altasib_geobase.SITE_ID='<?=SITE_ID?>';
</script>
<div id="altasib_geobase_window">
	<div id="altasib_geobase_window_block">
		<a href="#" title="<?=GetMessage("ALTASIB_GEOBASE_CLOSE");?>"><div id="altasib_geobase_close_kr"></div></a>
		<div id="altasib_geobase_page">
			<div class="altasib_geobase_yc_ttl"><?=GetMessage("ALTASIB_GEOBASE_YOUR_".$sMode)?></div><?

			if(($arResult["CODE"] == 00000000000 || empty($arResult["REGION"]["NAME"])) && empty($arResult["auto"]["CITY_NAME"])){?>

			<div class="altasib_geobase_your_city_block">
				<div class="altasib_geobase_your_city"><?=GetMessage("ALTASIB_GEOBASE_NOT_".$sMode);?></div>
			</div>
			<a id="altasib_geobase_yc_none" class="altasib_geobase_yc_btn" href="#"><?=GetMessage("ALTASIB_GEOBASE_SELECT_".$sMode);?></a><?
			}
			else {?>

			<div class="altasib_geobase_your_city_block">
				<span class="altasib_geobase_your_city"><?=$sCity;?></span><?
				if(!empty($sRegion)):?><span class="altasib_geobase_your_city_2"><?=$sRegion;?></span><?endif;
				if(!empty($sCoutnry)):?><span class="altasib_geobase_your_city_2"><?=$sCoutnry;?></span><?endif;
				?>

			</div>
			<a class="altasib_geobase_yc_btn" onclick="altasib_geobase.yc_yes_click('<?echo(!empty($arResult["CODE"])? $arResult["CODE"] : $arResult["REGION"]["CODE"]);?>'<?echo(!empty($arResult["REGION"]["CODE"]) ? ",'".$arResult["REGION"]["CODE"]."'" : "");?>); return false;" href="#"><?=GetMessage("ALTASIB_GEOBASE_YES");?></a>
			<a id="altasib_geobase_yc_n" class="altasib_geobase_yc_btn" href="#"><?=GetMessage("ALTASIB_GEOBASE_NO");?></a><?
			}?>

		</div>
	</div>
</div>

<?
$colorScheme = COption::GetOptionString("altasib.geobase", "color_scheme", "BRIGHT");
$colorTheme = COption::GetOptionString("altasib.geobase", "color_theme", "");
$colorOth = COption::GetOptionString("altasib.geobase", "color_other", "#0097f6");

if(!empty($colorOth) || !empty($colorTheme)){?>
<script type="text/javascript">
$('head').append("<link rel='stylesheet' type='text/css' href='<?
	echo CUtil::GetAdditionalFileURL($this->__folder."/themes/theme_".md5($colorTheme.'_'.$colorOth.'_'.$colorScheme).".css");
	?>' />");

<?}?>
</script>

<?if($arResult["POPUP_BACK"] != 'N'){
	?><div id="altasib_geobase_yc_backg"></div>
<?}
$frame->end();?>