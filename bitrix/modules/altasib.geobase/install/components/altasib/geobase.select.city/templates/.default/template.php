<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$arAuto = $arResult["auto"];
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
		if(!empty($arUsrCh["REGION"]["SOCR"]) && $arUsrCh["REGION"]["SOCR"] != GetMessage('ALTASIB_GEOBASE_SOCR_G'))
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

if($_REQUEST["AUTOLOAD"] != 'Y'):
?><span class="altasib_geobase_link"><?
	?><span class="altasib_geobase_link_prefix"><?
		if(isset($arParams["SPAN_LEFT"])){
			if(!empty($arParams["SPAN_LEFT"]) && trim($arParams["SPAN_LEFT"] != ''))
				echo $arParams["SPAN_LEFT"]."&nbsp;";
		}
		else
			echo GetMessage("ALTASIB_GEOBASE_MY_".$sMode)."&nbsp;";
	?></span><?
	?><div class="altasib_geobase_link_city" title="<?=$usrChoiceTitle;?>"><?
		if(!empty($usrSelCity))
			echo $usrSelCity;
		elseif($arParams["RIGHT_ENABLE"] == "Y" && ((is_array($arAutoDt) && !empty($arAutoDt["CITY"]["NAME"])) || (is_array($arAuto) && !empty($arAuto["CITY_NAME"]))))
		{
			if(is_array($arAutoDt) && isset($arAutoDt["CITY"]["NAME"]))
				echo $arAutoDt["CITY"]["NAME"];
			elseif(is_array($arAuto) && isset($arAuto["CITY_NAME"]))
				echo $arAuto["CITY_NAME"];
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
	?></div>
</span>
<?endif;?>
<?if($_REQUEST["get_select"] != 'Y'):?>
<script language="JavaScript">
if(typeof altasib_geobase=="undefined")
	var altasib_geobase={};
altasib_geobase.codes=jQuery.parseJSON('<?=json_encode($arResult['SEL_CODES']);?>');
altasib_geobase.socrs=['<?=implode("','",$arResult['SOCRS']);?>'];
altasib_geobase.is_mobile=false;
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

<div id="altasib_geobase_win">
	<div class="altasib_geobase_city">
		<div id="altasib_geobase_popup">
			<div id="altasib_geobase_close"><a href="#" title="<?=GetMessage("ALTASIB_GEOBASE_CLOSE");?>"></a></div>

			<div class="altasib_geobase_pu_i altasib_geobase_cutting">
				<div class="altasib_geobase_ttl"><?echo ($arResult['RU_ENABLE'] == "Y" ? GetMessage("ALTASIB_GEOBASE_SELECT_".$sMode) : GetMessage("ALTASIB_GEOBASE_YOUR_".$sMode)).":";?></div>
				<div class="altasib_geobase_cities">
					<ul class="<?echo (IsIE() ? 'altasib_geobase_list_ie' : 'altasib_geobase_fst');?>"><?
						$iLi = 0;
						if(!empty($arUsrCh['CODE'])){?>

<?						?><li class="altasib_geobase_act"><?
							?><a href="#" title="<?=$usrChoiceTitle;?>" id="altasib_geobase_list_<?=$arUsrCh["CODE"];?>" onclick="altasib_geobase.sc_onclk('<?=$arUsrCh["CODE"];?>');"><?
								if(is_array($arUsrCh["CITY"]) && isset($arUsrCh["CITY"]["NAME"]))
									echo ($arCity[] = $arUsrCh["CITY"]["NAME"]);
							$iLi++;
							?></a><?
						?></li><?
						}else if(!empty($arUsrCh['CITY']) && !empty($arUsrCh['CITY']['NAME'])){ // C_CODE
							$cityID = (!empty($arUsrCh["CODE"]) ? $arUsrCh["CODE"] :
								(!empty($arUsrCh["C_CODE"]) ? $arUsrCh["C_CODE"] : $arUsrCh["CITY"]));?>

<?						?><li class="altasib_geobase_act"><?
							?><a href="#" title="<?=$usrChoiceTitle;?>" id="altasib_geobase_list_<?echo str_replace(' ','_', $cityID);?>" onclick="altasib_geobase.sc_onclk('<?=$cityID;?>','<?=$arUsrCh["COUNTRY_CODE"];?>');"><?
								echo $usrSelCity;
								$iLi++;
							?></a><?
						?></li><?
						} else if(!empty($arUsrCh['CITY_NAME'])){
							$cityID = (!empty($arUsrCh["CODE"]) ? $arUsrCh["CODE"] : $arUsrCh["CITY_NAME"]);?>

<?						?><li class="altasib_geobase_act"><?
							?><a href="#" title="<?=$usrChoiceTitle;?>" id="altasib_geobase_list_<?echo str_replace(' ','_', $cityID);?>" onclick="altasib_geobase.sc_onclk('<?=$cityID;?>','<?=$arUsrCh["COUNTRY_CODE"];?>');"><?
								echo $usrSelCity;
								$iLi++;
							?></a><?
						?></li><?
						} else if(!empty($arUsrCh['REGION']) && !empty($arUsrCh['REGION']['NAME'])){
							$cityID = (!empty($arUsrCh["CODE"]) ? $arUsrCh["CODE"] : (!empty($arUsrCh['REGION']["CODE"]) ? $arUsrCh['REGION']["CODE"] : $arUsrCh["REGION"]['NAME']));?>

<?						?><li class="altasib_geobase_act"><?
							?><a href="#" title="<?=$usrChoiceTitle;?>" id="altasib_geobase_list_<?echo str_replace(' ','_', $cityID);?>" onclick="altasib_geobase.sc_onclk('<?=$cityID;?>','<?=$arUsrCh["COUNTRY_CODE"];?>','<?=$cityID;?>');"><?
								echo $usrChoiceTitle;
								$iLi++;
							?></a><?
						?></li><?
						}


						if(!empty($arAutoDt['CODE']) && !in_array($arAutoDt["CITY"]["NAME"], $arCity) && $arResult['AUTODETECT_ENABLE'] == "Y"){?>

<?						?><li class="altasib_geobase_auto"><?
							?><a id="altasib_geobase_list_<?=$arAutoDt["CODE"];?>" onclick="altasib_geobase.sc_onclk('<?=$arAutoDt["CODE"];?>');" title="<?echo $arAutoDt["CITY"]["SOCR"].'. '.$arAutoDt["CITY"]["NAME"].', '.$arAutoDt["REGION"]["FULL_NAME"]
							.(!empty($arAutoDt['DISTRICT']['SOCR']) ? ', '.$arAutoDt['DISTRICT']['NAME'].' '.$arAutoDt['DISTRICT']['SOCR'].'.' : '');?>" href="#"><?
								if(is_array($arAutoDt["CITY"]) && isset($arAutoDt["CITY"]["NAME"]))
									echo $arAutoDt["CITY"]["NAME"];
							$iLi++;
							?></a><?
						?></li><?
						}
						else if(!empty($arAutoDt['REGION']) && !empty($arAutoDt['REGION']['NAME']) && !in_array($arAutoDt["REGION"]["NAME"], $arCity) && $arResult['AUTODETECT_ENABLE'] == "Y"){?>

<?						?><li class="altasib_geobase_auto"><?
							?><a id="altasib_geobase_list_<?=(!empty($arAutoDt["CODE"]) ? $arAutoDt["CODE"] : $arAutoDt["REGION"]["CODE"]);?>" onclick="altasib_geobase.sc_onclk('<?=$arAutoDt["REGION"]["CODE"];?>','','<?=$arAutoDt["REGION"]["CODE"]?>');" title="<?=$arAutoDt["REGION"]["FULL_NAME"]?>" href="#"><?
								if(is_array($arAutoDt["REGION"]) && isset($arAutoDt["REGION"]["NAME"]))
									echo $arAutoDt["REGION"]["NAME"];

								if(!empty($arAutoDt["REGION"]["SOCR"]) && $arAutoDt["REGION"]["SOCR"] != GetMessage('ALTASIB_GEOBASE_SOCR_G'))
									echo " ".$arAutoDt["REGION"]["SOCR"];
							$iLi++;
							?></a><?
						?></li><?
						}else if(!empty($arAuto['CITY_NAME']) && !in_array($arAuto["CITY_NAME"], $arCity)
							&& $arAuto["CITY_NAME"] != $usrSelCity
							&& $arAuto["CITY_NAME"] != $arUsrCh["CITY"]
						){
							$bAutoSet = true; // show auto city
							foreach($arResult["SELECTED"] as $arSel){
								if ($arSel['C_NAME'] == $arAuto["CITY_NAME"]){
									$bAutoSet = false;
									break;
								}
							}

							if($bAutoSet){
								$cityID = (!empty($arAuto["CODE"]) ? $arAuto["CODE"] : $arAuto["CITY_NAME"]);?>

<?						?><li class="altasib_geobase_auto"><?
							?><a id="altasib_geobase_list_<?echo str_replace(' ','_', $cityID);?>" onclick="altasib_geobase.sc_onclk('<?=$cityID;?>','<?=$arResult["auto"]["COUNTRY_CODE"];?>');" title="<?echo $arAuto["CITY_NAME"]
								.(!empty($arAuto['REGION_NAME']) ? ', '.$arAuto['REGION_NAME'] : '')
								.(!empty($arAuto['COUNTRY_NAME']) ? ' ('.$arAuto['COUNTRY_NAME'].')' : '');?>" href="#"><?
								echo $arAuto["CITY_NAME"];
								$iLi++;
							?></a><?
						?></li><?
							}
						}

						if(IsIE()){ // if IE
							for($i=0, $icnt = count($arResult["SELECTED"]); $i<$icnt; $i++){
								$slct = $arResult["SELECTED"][$i];
								$iLi++;
						?>

<?						?><li<?echo($arResult["CODE"] == $slct["C_CODE"] ? ' class="altasib_geobase_act"' : '')?>><?
							?><a id="altasib_geobase_list_<?=$slct["C_CODE"]?>" onclick="altasib_geobase.sc_onclk('<?=$slct["C_CODE"]?>');" title="<?
							echo (!empty($slct["C_SOCR"]) ? $slct["C_SOCR"].'. ' : '').$slct["C_NAME"].(!empty($slct["R_FNAME"]) ? ', '.$slct["R_FNAME"] : '')
							.(isset($slct['D_NAME']) ? ', '.$slct['D_NAME'].' '.$slct['D_SOCR'].'.' : '')
							.(!empty($slct["CTR_NAME_RU"]) ? ', '.$slct["CTR_NAME_RU"] : '');?>" href="#"><?=$slct["C_NAME"];?></a><?
						?></li><?
								if($iLi == 6){
					?>

					</ul>
					<ul class="<?echo (IsIE() ? 'altasib_geobase_list_ie' : 'altasib_geobase_fst');?>"><?
									$iLi = 0;
								}
							}
						}
						else{ // regular browser (not IE)
							foreach($arResult["SELECTED"] as $sel){
						?>

<?						?><li<?echo($arResult["CODE"] == $sel["C_CODE"] ? ' class="altasib_geobase_act"' : '')?>><?
								if($sel["C_CODE"] == $sel["R_ID"]){

							?><a id="altasib_geobase_list_<?=$sel["C_CODE"]?>" onclick="altasib_geobase.sc_onclk('<?=$sel["C_CODE"]?>','','<?=$sel["C_CODE"]?>');" title="<?
							echo (!empty($sel["R_FNAME"]) ? $sel["R_FNAME"] : '')
							.(!empty($sel["CTR_NAME_RU"]) ? ', '.$sel["CTR_NAME_RU"] : '');?>" href="#"><?
								if(strlen($sel["R_FNAME"]) <= 20):
									echo $sel["R_FNAME"];
								else:
									echo $sel["C_NAME"];
									if(!empty($sel["C_SOCR"]) && $sel["C_SOCR"] != GetMessage('ALTASIB_GEOBASE_SOCR_G'))
										echo " ".$sel["C_SOCR"];
								endif;
							?></a><?
						?></li><?
								}
								else{
							?><a id="altasib_geobase_list_<?=$sel["C_CODE"]?>" onclick="altasib_geobase.sc_onclk('<?=$sel["C_CODE"]?>');" title="<?
							echo (!empty($sel["C_SOCR"]) ? $sel["C_SOCR"].'. ' : '').$sel["C_NAME"].(!empty($sel["R_FNAME"]) ? ', '.$sel["R_FNAME"] : '')
							.(isset($sel['D_NAME']) ? ', '.$sel['D_NAME'].' '.$sel['D_SOCR'].'.' : '')
							.(!empty($sel["CTR_NAME_RU"]) ? ', '.$sel["CTR_NAME_RU"] : '');?>" href="#"><?=$sel["C_NAME"];?></a><?
						?></li><?
								}
							}
						}?>

						</ul>
					<div class="altasib_geobase_clear"></div>
				</div><?
				if($arResult['ONLY_SELECT'] != "Y"):?>

				<div class="altasib_geobase_title2"><?=GetMessage("ALTASIB_GEOBASE_ENTER_FIELD");?></div>
				<a id="altasib_geobase_btn" class="altasib_geobase_disabled" href="#"><?=GetMessage("ALTASIB_GEOBASE_THIS_IS_MY_".$sMode);?></a>

				<div class="altasib_geobase_find">
					<input id="altasib_geobase_search" name="altasib_geobase_search" type="text" placeholder="<?=GetMessage('ALTASIB_GEOBASE_ENTER_'.$sMode);?>" autocomplete="off"><br/>
					<div id="altasib_geobase_info"></div>
				</div><?
				endif;?>

			</div>
		</div>
	</div>
</div>
<?if($arResult["POPUP_BACK"] != 'N'){
	?><div id="altasib_geobase_popup_back"></div>
<?}?>

<?
	if($arResult["SHOW_SMALL"] == "Y"):?>
<script type="text/javascript">
var a='<div class="altasib_geobase_sml_win"><div class="altasib_geobase_sml_win_block"><div class="altasib_geobase_sml_ctr"><div class="altasib_geobase_sml_block">'+
	'<span class="altasib_geobase_sml_your"><?echo (!empty($arParams["SMALL_TEXT"]) ? $arParams["SMALL_TEXT"] : GetMessage('ALTASIB_GEOBASE_THIS'));?></span>'+
	'</div>'+
	'<a class="altasib_geobase_sml_yes altasib_geobase_sml_btn" onclick="altasib_geobase.sc_onclk(\'<?echo(!empty($arAutoDt["CODE"])? $arAutoDt["CODE"] : $arAutoDt["REGION"]["CODE"]);?>\'<?echo(!empty($arAutoDt["REGION"]["CODE"]) ? ",\'".$arAutoDt["REGION"]["CODE"]."\'" : "");?>); return false;" href="#"><?=GetMessage('ALTASIB_GEOBASE_Y')?></a>'+
	'<a class="altasib_geobase_sml_no altasib_geobase_sml_btn" href="#" onclick="altasib_geobase.sml_no();return false;"><?=GetMessage('ALTASIB_GEOBASE_N')?></a>'+
	'</div></div></div>';
<?		if(!$notAutoShowPopup):?>
$(document).ready(function(){
	if(!$(".altasib_geobase_link_city").children('.altasib_geobase_sml_win').length>0){
		$(".altasib_geobase_link_city").append(a);
		window.setTimeout(function(){$('.altasib_geobase_sml_win').animate({opacity:1},1200)},2000);
	}
	// $(".altasib_geobase_link").has('.altasib_geobase_sml_win').append(a);
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
<?$frame->end(); ?>