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

if(empty($arResult))
	return;

$bShowName = ($arParams["NO_NAME"] != "Y");
$bShowDef = ($arParams["SHOW_DEFAULT"] != "N");
?>

<div class="altasib_geobase_uf">
<?
if($arParams["SHOW_CITY"] == "Y"):
	if(!empty($arResult["SELECT_CITY"])):?>
	<div class="altasib_geobase_uf_list">
<?		if($bShowName):?>
		<span class="altasib_geobase_uf_name"><?=GetMessage("ALTASIB_SHOW_CITY")?>:</span>
<?		endif?>
		<span id="altasib_geobase_uf_city"><?=$arResult["SELECT_CITY"]?></span>
	</div><?
	endif;
endif;?>

<?if($arParams["SHOW_REGION"] == "Y"):
	if(!empty($arResult["SELECT_REGION"])):?>
	<div class="altasib_geobase_uf_list">
<?		if($bShowName):?>
		<span class="altasib_geobase_uf_name"><?=GetMessage("ALTASIB_SHOW_REGION")?>:</span>
<?		endif?>
		<span id="altasib_geobase_uf_region"><?=$arResult["SELECT_REGION"]?></span>
	</div><?
	endif;
endif;?>

<?if($arParams["SHOW_COUNTY"] == "Y"):
	if(!empty($arResult["SELECT_COUNTY"])):?>
	<div class="altasib_geobase_uf_list">
<?		if($bShowName):?>
		<span class="altasib_geobase_uf_name"><?=GetMessage("ALTASIB_SHOW_COUNTY")?>:</span>
<?		endif?>
		<span id="altasib_geobase_uf_county"><?=$arResult["SELECT_COUNTY"]?></span>
	</div><?
	endif;
endif;?>

<?if($arParams["SHOW_COUNTRY"] == "Y"):
	if(!empty($arResult["SELECT_COUNTRY"])):?>
	<div class="altasib_geobase_uf_list">
<?		if($bShowName):?>
		<span class="altasib_geobase_uf_name"><?=GetMessage("ALTASIB_SHOW_COUNTRY")?>:</span>
<?		endif?>
		<span id="altasib_geobase_uf_country"><?=$arResult["SELECT_COUNTRY"]?></span>
	</div><?
	endif;
endif;?>

<?if($arParams["SHOW_COUNTRY_CODE"] == "Y"):
	if(!empty($arResult["SELECT_COUNTRY_CODE"])):?>
	<div class="altasib_geobase_uf_list">
<?		if($bShowName):?>
		<span class="altasib_geobase_uf_name"><?=GetMessage("ALTASIB_SHOW_COUNTRY_CODE")?>:</span>
<?		endif?>
		<span id="altasib_geobase_uf_ccode"><?=$arResult["SELECT_COUNTRY_CODE"]?></span>
	</div><?
	endif;
endif;?>

<?if($arParams["SHOW_COORDINATES"] == "Y"):
	if(!empty($arResult["SELECT_LATITUDE"])):?>
	<div class="altasib_geobase_uf_list">
<?		if($bShowName):?>
		<span class="altasib_geobase_uf_name"><?=GetMessage("ALTASIB_SHOW_LAT")?>:</span>
<?		endif?>
		<span id="altasib_geobase_uf_lat"><?=$arResult["SELECT_LATITUDE"]?></span>
	</div><?
	endif;
	if(!empty($arResult["SELECT_LONGITUDE"])):?>
	<div class="altasib_geobase_uf_list">
<?		if($bShowName):?>
		<span class="altasib_geobase_uf_name"><?=GetMessage("ALTASIB_SHOW_LONG")?>:</span>
<?		endif?>
		<span id="altasib_geobase_uf_long"><?=$arResult["SELECT_LONGITUDE"]?></span>
	</div><?
	endif;
endif;?>
<?
if(!empty($arResult["UF"]) && count($arResult["UF"]) > 0):
	foreach($arResult["UF"] as $field):
		$code = $field["ENTITY_ID"]."_".$field["FIELD_NAME"];
		$bSetVal = false;
?>
	<div class="altasib_geobase_uf_list <?=$code?>">
<?		if(is_array($field["VALUE"]) && count($field["VALUE"]) > 0
			|| (!is_array($field["VALUE"]) && strlen($field["VALUE"]) > 0)
			|| (empty($field["VALUE"]) && $bShowDef && strlen(trim($field["SETTINGS"]["DEFAULT_VALUE"])) > 0)
		): // not empty value or default ?>
<?
			if(is_array($field["VALUE"]) || is_array($field["DISPLAY_VALUE"])):?>
<?				if(count($field["DISPLAY_VALUE"]) > 0 || count($field["VALUE"]) > 0):?>
<?					if($bShowName):?>
		<span class="altasib_geobase_uf_name"><?=$field["LIST_COLUMN_LABEL"]?>:&nbsp; </span>
<?					endif?>
		<span class="altasib_geobase_uf_val_list">
<?						// display values
					if(!empty($field["DISPLAY_VALUE"])):?>
<?						if(count($field["DISPLAY_VALUE"]) == 1 && !is_array($field["DISPLAY_VALUE"][0])):
?>
			<span class="altasib_geobase_uf_value"><?=implode(', ', $field["DISPLAY_VALUE"])?></span>
<?						else:?>
<?
							foreach($field["DISPLAY_VALUE"] as $value):
								if(strlen(trim($value["VALUE"])) > 0):
									$bSetVal = true;?>
			<div class="altasib_geobase_uf_value"><?=$value["VALUE"]?></div>
<?								elseif($bShowDef && !$bSetVal && strlen(trim($field["SETTINGS"]["DEFAULT_VALUE"]))>0):?>
			<span class="altasib_geobase_uf_value"><?=trim($field["SETTINGS"]["DEFAULT_VALUE"]);?></span>
<?								endif;?>
<?							endforeach?>
<?						endif;?>
<?					else:?>
<?						if(count($field["VALUE"]) == 1):?>
			<span class="altasib_geobase_uf_value"><?=implode(', ', $field["VALUE"])?></span>
<?						else:?>
<?							foreach($field["VALUE"] as $value):
								if(strlen(trim($value)) > 0):
									$bSetVal = true;?>
			<div class="altasib_geobase_uf_value"><?=$value?></div>
<?								elseif($bShowDef && !$bSetVal && strlen(trim($field["SETTINGS"]["DEFAULT_VALUE"]))>0):?>
			<span class="altasib_geobase_uf_value"><?=trim($field["SETTINGS"]["DEFAULT_VALUE"]);?></span>
<?								endif;?>
<?							endforeach?>
<?						endif;?>
<?					endif;?>
		</span>
<?				endif;?>
<?			else: // not array ?>
<?
				if($bShowName):?>
		<span class="altasib_geobase_uf_name"><?=$field["LIST_COLUMN_LABEL"]?>:&nbsp; </span>
<?				endif?>
<?
				if(strlen(trim($field["DISPLAY_VALUE"])) > 0):?>
		<span class="altasib_geobase_uf_value"><?=$field["DISPLAY_VALUE"]?></span>
<?				elseif(strlen(trim($field["VALUE"])) > 0):?>
		<span class="altasib_geobase_uf_value"><?=$field["VALUE"]?></span>
<?				elseif($bShowDef && strlen(trim($field["SETTINGS"]["DEFAULT_VALUE"]))>0):?>
		<span class="altasib_geobase_uf_value"><?=trim($field["SETTINGS"]["DEFAULT_VALUE"]);?></span>
<?				endif;?>
<?			endif;?>
<?		else: // empty value and default ?>
<?			if($arParams["NO_EMPTY"] != "Y"):?>
<?				if($bShowName):?>
		<span class="altasib_geobase_uf_name"><?=$field["LIST_COLUMN_LABEL"]?>:&nbsp; </span>
<?				endif?>
<?			endif?>
<?		endif;?>
	</div>
<?	endforeach;?>
<?endif;?>
</div>
<?
$frame->end(); ?>