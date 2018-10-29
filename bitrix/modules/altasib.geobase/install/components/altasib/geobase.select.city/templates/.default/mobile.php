<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=1">
/**
 * @var $arResult array
 * @var $arParams array
 * @var $APPLICATION CMain
 * @var $USER CUser
 * @var $component CBitrixComponent
 * @var $this CBitrixComponentTemplate
 * @var $city
 */

$this->setFrameMode(true);
$frame = $this->createFrame()->begin("");

$usrChoiceTitle = "";
$usrSelCity = "";
$arUsrCh = $arResult["USER_CHOICE"];
$arAutoDt = $arResult["AUTODETECT"];
$sMode = $arResult['MODE_LOCATION'];
$arCity = array();

if(!empty($arUsrCh) && is_array($arUsrCh))
{
	if(is_array($arUsrCh["CITY"]) && !empty($arUsrCh["CITY"]["SOCR"]))
	{
		$usrChoiceTitle = $arUsrCh["CITY"]["SOCR"].'. '.$arUsrCh["CITY"]["NAME"].', '.$arUsrCh["REGION"]["FULL_NAME"]
		.(!empty($arUsrCh['DISTRICT']['SOCR']) ? ', '.$arUsrCh['DISTRICT']['NAME'].' '.$arUsrCh['DISTRICT']['SOCR'].'.' : '');
		$usrSelCity = (!empty($arUsrCh["CITY"]["NAME"]) ? $arUsrCh["CITY"]["NAME"] : '');
	}
	if(is_array($arUsrCh["CITY"]) && empty($arUsrCh["CITY"]["NAME"])
		&& is_array($arUsrCh["REGION"]) && !empty($arUsrCh["REGION"]["NAME"]))
	{
		$usrChoiceTitle = $arUsrCh["REGION"]["FULL_NAME"];
		$usrSelCity = $arUsrCh["REGION"]["NAME"];
		if(!empty($arUsrCh["REGION"]["SOCR"]) && $arUsrCh["REGION"]["SOCR"] != GetMessage('ALTASIB_GEOBASE_G'))
			$usrSelCity .= " ".$arUsrCh["REGION"]["SOCR"];
	}
	elseif(!empty($arUsrCh["COUNTRY_RU"]) || !empty($arUsrCh["CITY_RU"]))
	{
		$usrChoiceTitle = (($arResult['RU_ENABLE'] == "Y") ? $usrSelCity = $arUsrCh["CITY_RU"] :
				(!empty($arUsrCh["CITY"]) ? $usrSelCity = $arUsrCh["CITY"] : ''))
			.(!empty($arUsrCh['REGION']) ? ', '.$arUsrCh['REGION'] : '')
			.((!empty($arUsrCh["COUNTRY_RU"]) && $arResult['RU_ENABLE'] == "Y") ?
			' ('.$arUsrCh["COUNTRY_RU"].')' : (!empty($arUsrCh["COUNTRY"]) ? ' ('.$arUsrCh["COUNTRY"].')' : ''));
	}
	elseif(!empty($arUsrCh["CITY_NAME"]))
	{
		$usrChoiceTitle = ($usrSelCity = $arUsrCh["CITY_NAME"])
			.(!empty($arUsrCh['REGION_NAME']) ? ', '.$arUsrCh['REGION_NAME'] : '')
			.(!empty($arUsrCh['COUNTRY_NAME']) ? ' ('.$arUsrCh['COUNTRY_NAME'].')' : '');
	}
}

$notAutoShowPopup = false;

if($_REQUEST["AUTOLOAD"] != 'Y'):?>
<span class="altasib_geobase_mb_link">
	<span class="altasib_geobase_mb_link_prefix"><?
		if(isset($arParams["SPAN_LEFT"])){
			if(!empty($arParams["SPAN_LEFT"]) && trim($arParams["SPAN_LEFT"] != ''))
				echo $arParams["SPAN_LEFT"]."&nbsp;";
		}
		else
			echo GetMessage("ALTASIB_GEOBASE_MY_".$sMode)."&nbsp;";
	?></span><?
	?><span class="altasib_geobase_mb_link_city" title="<?=$usrChoiceTitle;?>"><?
		if(!empty($usrSelCity))
			echo $usrSelCity;
		elseif($arParams["RIGHT_ENABLE"] == "Y" && (is_array($arAutoDt) || is_array($arResult['auto'])))
		{
			if(is_array($arAutoDt) && isset($arAutoDt["CITY"]["NAME"]))
				echo $arAutoDt["CITY"]["NAME"];
			elseif(is_array($arResult['auto']) && isset($arResult['auto']["CITY_NAME"]))
				echo $arResult['auto']["CITY_NAME"];
		}
		elseif(isset($arParams["SPAN_RIGHT"]))
		{
			echo $arParams["SPAN_RIGHT"];
			$notAutoShowPopup = true;
		}
		else
		{
			echo GetMessage("ALTASIB_GEOBASE_SELECT_".$sMode);
			$notAutoShowPopup = true;
		}
	?></span>
</span>
<?endif;?>
<?if($_REQUEST["get_select"] != 'Y'):?>
<script language="JavaScript">
if(typeof altasib_geobase=="undefined")
	var altasib_geobase={};
altasib_geobase.codes=jQuery.parseJSON('<?=json_encode($arResult['SEL_CODES']);?>');
altasib_geobase.socrs=['<?=implode("','",$arResult['SOCRS']);?>'];
altasib_geobase.is_mobile=true;
altasib_geobase.COOKIE_PREFIX='<?=COption::GetOptionString("main", "cookie_name", "BITRIX_SM");?>';
altasib_geobase.bitrix_sessid='<?=bitrix_sessid();?>';
altasib_geobase.SITE_ID='<?=SITE_ID?>';
</script>
<?endif?>
<?if($arParams["LOADING_AJAX"] != 'Y' || $_REQUEST["get_select"] == 'Y'):?>
<script language="JavaScript">
if(typeof altasib_geobase=="undefined")var altasib_geobase={};
altasib_geobase.bitrix_sessid='<?=bitrix_sessid();?>';
</script>

<div id="altasib_geobase_mb_win">
	<div class="altasib_geobase_mb_city">
		<div id="altasib_geobase_mb_popup">
			<div id="altasib_geobase_mb_header">
				<div id="altasib_geobase_mb_close"><?
					?><a href="#" title="<?=GetMessage("ALTASIB_GEOBASE_CLOSE");?>"></a><?
				?></div>
				<div class="altasib_geobase_mb_ttl"><?=GetMessage("ALTASIB_GEOBASE_SELECT_".$sMode).":"?></div>
			</div>
			<div class="altasib_geobase_mb_pu_i altasib_geobase_mb_cutting"><?
				if($arResult['ONLY_SELECT'] != "Y"):?>
					<div id="altasib_geobase_mb_find" class="altasib_geobase_mb_find">
						<input id="altasib_geobase_mb_search" name="altasib_geobase_mb_search" type="text" placeholder="<?=GetMessage('ALTASIB_GEOBASE_ENTER_'.$sMode);?>" autocomplete="off"><br/>
						<div id="altasib_geobase_mb_info"></div>
					</div>
					<a id="altasib_geobase_mb_btn" class="altasib_geobase_mb_disabled" href="#"><?=GetMessage("ALTASIB_GEOBASE_THIS_IS_MY_".$sMode);?></a><?
				endif;?><?
				?><div id="altasib_geobase_mb_cities" class="altasib_geobase_mb_cities">
					<ul class="<?echo (IsIE() ? 'altasib_geobase_mb_list_ie' : 'altasib_geobase_mb_fst');?>">
						<?$iLi = 0;
						if(!empty($arUsrCh['CODE'])){
						?><li class="altasib_geobase_mb_act"><?
							?><a href="#" title="<?=$usrChoiceTitle;?>" id="altasib_geobase_mb_list_<?=$arUsrCh["CODE"];?>" onclick="altasib_geobase.sc_onclk('<?=$arUsrCh["CODE"];?>');"><?
								if(isset($arUsrCh["CITY"]["NAME"]))
									echo ($arCity[] = $arUsrCh["CITY"]["NAME"]);
							$iLi++;
							?></a><?
						?></li><?
						}else if(!empty($arUsrCh['CITY'])){
							$cityID = (!empty($arUsrCh["CODE"]) ? $arUsrCh["CODE"] : $arUsrCh["CITY"]);
						?><li class="altasib_geobase_mb_act"><?
							?><a href="#" title="<?=$usrChoiceTitle;?>" id="altasib_geobase_mb_list_<?echo str_replace(' ','_', $cityID);?>" onclick="altasib_geobase.sc_onclk('<?=$cityID;?>','<?=$arUsrCh["COUNTRY_CODE"];?>');"><?
								echo $usrSelCity;
								$iLi++;
							?></a><?
						?></li><?
						} else if(!empty($arUsrCh['CITY_NAME'])){
							$cityID = (!empty($arUsrCh["CODE"]) ? $arUsrCh["CODE"] : $arUsrCh["CITY_NAME"]);
						?><li class="altasib_geobase_mb_act"><?
							?><a href="#" title="<?=$usrChoiceTitle;?>" id="altasib_geobase_mb_list_<?echo str_replace(' ','_', $cityID);?>" onclick="altasib_geobase.sc_onclk('<?=$cityID;?>','<?=$arUsrCh["COUNTRY_CODE"];?>');"><?
								echo $usrSelCity;
								$iLi++;
							?></a><?
						?></li><?
						} else if(!empty($arUsrCh['REGION']) && !empty($arUsrCh['REGION']['NAME'])){
							$cityID = (!empty($arUsrCh["CODE"]) ? $arUsrCh["CODE"] : (!empty($arUsrCh['REGION']["CODE"]) ? $arUsrCh['REGION']["CODE"] : $arUsrCh["REGION"]['NAME']));?>

<?						?><li class="altasib_geobase_mb_act"><?
							?><a href="#" title="<?=$usrChoiceTitle;?>" id="altasib_geobase_mb_list_<?echo str_replace(' ','_', $cityID);?>" onclick="altasib_geobase.sc_onclk('<?=$cityID;?>','<?=$arUsrCh["COUNTRY_CODE"];?>', '<?=$cityID;?>');"><?
								echo $usrChoiceTitle;
								$iLi++;
							?></a><?
						?></li><?
						}
						?>

						<?if(isset($arAutoDt['CODE']) && !in_array($arAutoDt["CITY"]["NAME"], $arCity) && $arResult['AUTODETECT_ENABLE'] == "Y"){
						?><li class="altasib_geobase_mb_auto"><?
							?><a href="#" title="<?echo $arAutoDt["CITY"]["SOCR"].'. '.$arAutoDt["CITY"]["NAME"].', '.$arAutoDt["REGION"]["FULL_NAME"]
							.(!empty($arAutoDt['DISTRICT']['SOCR']) ? ', '.$arAutoDt['DISTRICT']['NAME'].' '.$arAutoDt['DISTRICT']['SOCR'].'.' : '');?>" id="altasib_geobase_mb_list_<?=$arAutoDt["CODE"];?>" onclick="altasib_geobase.sc_onclk('<?=$arAutoDt["CODE"];?>');"><?
								if($arAutoDt["CITY"]["NAME"]) echo $arAutoDt["CITY"]["NAME"];
							$iLi++;
							?></a><?
						?></li><?
						}
						else if(!empty($arAutoDt['REGION']) && !empty($arAutoDt['REGION']['NAME']) && !in_array($arAutoDt["REGION"]["NAME"], $arCity) && $arResult['AUTODETECT_ENABLE'] == "Y"){
						?><li class="altasib_geobase_mb_auto"><?
							?><a href="#" title="<?=$arAutoDt["REGION"]["FULL_NAME"];?>" id="altasib_geobase_mb_list_<?=(!empty($arAutoDt["CODE"]) ? $arAutoDt["CODE"] : $arAutoDt["REGION"]["CODE"]);?>" onclick="altasib_geobase.sc_onclk('<?=$arAutoDt["REGION"]["CODE"];?>','','<?=$arAutoDt["REGION"]["CODE"]?>');"><?
								if(is_array($arAutoDt["REGION"]) && isset($arAutoDt["REGION"]["NAME"]))
									echo $arAutoDt["REGION"]["NAME"];

								if(!empty($arAutoDt["REGION"]["SOCR"]) && $arAutoDt["REGION"]["SOCR"] != GetMessage('ALTASIB_GEOBASE_SOCR_G'))
									echo " ".$arAutoDt["REGION"]["SOCR"];
							$iLi++;
							?></a><?
						?></li><?

						}elseif(!empty($arResult['auto']['CITY_NAME']) && !in_array($arResult['auto']["CITY_NAME"], $arCity)
							&& $arResult['auto']["CITY_NAME"] != $usrSelCity
							&& $arResult['auto']["CITY_NAME"] != $arUsrCh["CITY"]
						){
							$bAutoSet = true; // show auto city
							foreach($arResult["SELECTED"] as $arSel){
								if ($arSel['C_NAME'] == $arResult['auto']["CITY_NAME"]){
									$bAutoSet = false;
									break;
								}
							}

							if($bAutoSet){
								$cityID = (!empty($arResult['auto']["CODE"]) ? $arResult['auto']["CODE"] : $arResult['auto']["CITY_NAME"]);

						?><li class="altasib_geobase_mb_auto"><?
							?><a href="#" title="<?echo $arResult['auto']["CITY_NAME"]
							.(!empty($arResult['auto']['REGION_NAME']) ? ', '.$arResult['auto']['REGION_NAME'] : '')
							.(!empty($arResult['auto']['COUNTRY_NAME']) ? ' ('.$arResult['auto']['COUNTRY_NAME'].')' : '');?>" id="altasib_geobase_mb_list_<?echo str_replace(' ','_', $cityID);?>" onclick="altasib_geobase.sc_onclk('<?=$cityID;?>','<?=$arResult["auto"]["COUNTRY_CODE"];?>');"><?
								if(isset($arResult['auto']["CITY_NAME"])) echo $arResult['auto']["CITY_NAME"];
							$iLi++;
							?></a><?
						?></li><?
							}
						}?>

						<?if(IsIE()){ // if IE
							for($i=0; $i<count($arResult["SELECTED"]); $i++){
								$slct = $arResult["SELECTED"][$i];
								$iLi++;
						?><li<?echo($arResult["CODE"] == $slct["C_CODE"] ? ' class="altasib_geobase_mb_act"' : '')?>><?
							?><a id="altasib_geobase_mb_list_<?=$slct["C_CODE"]?>" onclick="altasib_geobase.sc_onclk('<?=$slct["C_CODE"]?>');" title="<?echo $slct["C_SOCR"].'. '.$slct["C_NAME"].', '.$slct["R_FNAME"].(isset($slct['D_NAME']) ? ', '.$slct['D_NAME'].' '.$slct['D_SOCR'].'.' : '');?>" href="#"><?=$slct["C_NAME"];?></a><?
						?></li><?
								if($iLi == 6){
					?>

					</ul>
					<ul class="<?echo (IsIE() ? 'altasib_geobase_mb_list_ie' : 'altasib_geobase_mb_fst');?>"><?
									$iLi = 0;
								}
							}
						}
						else{ // regular browser (not IE)
							foreach($arResult["SELECTED"] as $sel){
						?><li<?echo($arResult["CODE"] == $sel["C_CODE"] ? ' class="altasib_geobase_mb_act"' : '')?>><?
								if($sel["C_CODE"] == $sel["R_ID"]){

							?><a id="altasib_geobase_mb_list_<?=$sel["C_CODE"]?>" onclick="altasib_geobase.sc_onclk('<?=$sel["C_CODE"]?>','','<?=$sel["C_CODE"]?>');" title="<?
								echo (!empty($sel["R_FNAME"]) ? $sel["R_FNAME"] : '')
							.(!empty($sel["CTR_NAME_RU"]) ? ', '.$sel["CTR_NAME_RU"] : '');?>" href="#"><?
								echo $sel["C_NAME"];
								if(!empty($sel["C_SOCR"]) && $sel["C_SOCR"] != GetMessage('ALTASIB_GEOBASE_G'))
									echo " ".$sel["C_SOCR"];
							?></a><?
						?></li><?
								}
								else{
							?><a id="altasib_geobase_mb_list_<?=$sel["C_CODE"]?>" onclick="altasib_geobase.sc_onclk('<?=$sel["C_CODE"]?>');" title="<?
							echo (!empty($sel["C_SOCR"]) ? $sel["C_SOCR"].'. ' : '').$sel["C_NAME"].(!empty($sel["R_FNAME"]) ? ', '.$sel["R_FNAME"] : '')
							.(isset($sel['D_NAME']) ? ', '.$sel['D_NAME'].' '.$sel['D_SOCR'].'.' : '')
							.(!empty($sel["CTR_NAME_RU"]) ? ', '.$sel["CTR_NAME_RU"] : '');?>" href="#"><?=$sel["C_NAME"];?></a><?
						?></li><?
								}
							}
						}?>

					</ul>
					<div class="altasib_geobase_mb_clear"></div>
				</div>
				<a id="all_cities_button_mobile" href="#"><?=GetMessage("ALTASIB_GEOBASE_ALL_".$sMode);?></a>
			</div>
		</div>
	</div>
</div>
<?
	if($arResult["POPUP_BACK"] != 'N'):
	?><div id="altasib_geobase_mb_popup_back"></div>
<?	endif;?>

<?
	if($arResult["SHOW_SMALL"] == "Y"):?>
<script type="text/javascript">
var a='<div class="altasib_geobase_sml_win"><div class="altasib_geobase_sml_win_block"><div class="altasib_geobase_sml_ctr"><div class="altasib_geobase_sml_block">'+
	'<span class="altasib_geobase_sml_your"><?echo (!empty($arParams["SMALL_TEXT"]) ? $arParams["SMALL_TEXT"] : GetMessage('ALTASIB_GEOBASE_THIS'));?></span>'+
	'</div>'+
	'<a class="altasib_geobase_sml_yes altasib_geobase_sml_btn" onclick="altasib_geobase.sc_onclk(\'<?echo(!empty($arAutoDt["CODE"])? $arAutoDt["CODE"] : $arAutoDt["REGION"]["CODE"]);?>\'<?echo(!empty($arAutoDt["REGION"]["CODE"]) ? ",\'".$arAutoDt["REGION"]["CODE"]."\'" : "");?>); return false;" href="#"><?=GetMessage('ALTASIB_GEOBASE_Y')?></a>'+
	'<a class="altasib_geobase_sml_no altasib_geobase_sml_btn" href="#" onclick="altasib_geobase.sml_no(event);return false;"><?=GetMessage('ALTASIB_GEOBASE_N')?></a>'+
	'</div></div></div>';
<?		if(!$notAutoShowPopup):?>
$(document).ready(function(){
	if(!$(".altasib_geobase_mb_link_city").children('.altasib_geobase_sml_win').length>0){
		$(".altasib_geobase_mb_link_city").append(a);
		window.setTimeout(function(){$('.altasib_geobase_sml_win').animate({opacity:1},1200)}, 2000);
	}
});
<?		endif;?>
</script>
<?	endif;?>
<?
$colorScheme = COption::GetOptionString("altasib.geobase", "color_scheme", "BRIGHT");
$colorTheme = COption::GetOptionString("altasib.geobase", "color_theme", "");
$colorOth = COption::GetOptionString("altasib.geobase", "color_other", "#0097f6");

if(!empty($colorOth) || !empty($colorTheme)){
?>
<script type="text/javascript">
$(document).ready(function(){
	$('head').append("<link rel='stylesheet' type='text/css' href='<?
		echo CUtil::GetAdditionalFileURL($this->__folder."/themes/theme_".md5($colorTheme.'_'.$colorOth.'_'.$colorScheme).".css");
		?>' />");
});
<?}?>
</script>

<?endif?>
<?$frame->end();?>