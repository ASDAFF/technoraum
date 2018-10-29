<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$module_id = "altasib.geobase";
$incMod = CModule::IncludeModuleEx($module_id);

$bNewVers = ((defined("SM_VERSION") && version_compare(SM_VERSION, "15.0.7") >= 0) ? true : false);
if($_REQUEST['bxsender'] != 'fileman_html_editor' && (!$bNewVers || $_REQUEST["edit_file"] == "template")):?>
<div style="background-color: #fff; padding: 0; border-top: 1px solid #8E8E8E; border-bottom: 1px solid #8E8E8E; margin-bottom: 15px;"><div style="background-color: #8E8E8E; height: 30px; padding: 7px; border: 1px solid #fff">
	<a href="http://www.is-market.ru?param=cl" target="_blank"><img src="/bitrix/images/altasib.geobase/is-market.gif" style="float: left; margin-right: 15px;" border="0" /></a>
	<div style="margin: 13px 0px 0px 0px">
		<a href="http://www.is-market.ru?param=cl" target="_blank" style="color: #fff; font-size: 10px; text-decoration: none"><?=GetMessage("ALTASIB_IS")?></a>
	</div>
</div></div>
<?
	if($incMod == '0')
	{
		CAdminMessage::ShowMessage(Array("MESSAGE"=>GetMessage("ALTASIB_GEOBASE_NF", Array("#MODULE#" => $module_id)), "HTML"=>true, "TYPE"=>"ERROR"));
	}
	elseif($incMod == '2')
	{
		?><span class="errortext"><?=GetMessage("ALTASIB_GEOBASE_DEMO_MODE", Array("#MODULE#" => $module_id))?></span><br/><?
	}
	elseif($incMod == '3')
	{
		CAdminMessage::ShowMessage(Array("MESSAGE"=>GetMessage("ALTASIB_GEOBASE_DEMO_EXPIRED", Array("#MODULE#" => $module_id)), "HTML"=>true, "TYPE"=>"ERROR"));
	}

endif;

if ($incMod == '0' || $incMod == '3')
	return false;

// p($incMod);

$arUFList = array();

global $USER_FIELD_MANAGER;
$arUFields = $USER_FIELD_MANAGER->GetUserFields("ALTASIB_GEOBASE", false, LANGUAGE_ID);
foreach($arUFields as $arFld)
{
	$arUFList[$arFld["FIELD_NAME"]] = "[".$arFld["FIELD_NAME"]."] ".$arFld["LIST_COLUMN_LABEL"];
}
// $arPropAuto[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];

$arComponentParameters = Array(
	"GROUPS" => array(
		"FIELDS" => array(
			"NAME" => GetMessage("SECTION_FIELDS"),
			"SORT" => "200",
		),
		"NEAREST" => array(
			"NAME" => GetMessage("SECTION_NEAREST"),
			"SORT" => "300",
		),
	),
	"PARAMETERS" => Array(
		/* "FORM_NAME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ALTASIB_KLADR_INPUT_FORM_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$arProperties["FROM_NAME"]}',
		),
		"FIELD_NAME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ALTASIB_KLADR_INPUT_FIELD_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$arProperties["FIELD_NAME"]}',
		),
		"VALUE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ALTASIB_KLADR_INPUT_VALUE"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$arProperties["VALUE"]}',
		), */
		"NO_NAME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ALTASIB_GEOBASE_UF_NOTSHOW_NAME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N',
		),
		"SHOW_DEFAULT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ALTASIB_GEOBASE_UF_SHOW_DEFAULT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'Y',
		),
		"NO_EMPTY" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ALTASIB_GEOBASE_UF_NOTSHOW_EMPTY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N',
		),
		"FIND_NEAREST" => array(
			"PARENT" => "NEAREST",
			"NAME" => GetMessage("ALTASIB_GEOBASE_UF_FIND_NEAREST"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y",
		),
		"USERFIELD_LIST" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage("ALTASIB_USERFIELD_LIST"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arUFList,
			"DEFAULT" => array("UF_GB_SELCITY_CT", "UF_ALXGB_DESCR"),
		),
		"SHOW_CITY" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage("ALTASIB_GEOBASE_UF_SHOW_CITY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N',
		),
		"SHOW_REGION" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage("ALTASIB_GEOBASE_UF_SHOW_REGION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N',
		),
		"SHOW_COUNTY" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage("ALTASIB_GEOBASE_UF_SHOW_COUNTY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N',
		),
		"SHOW_COUNTRY" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage("ALTASIB_GEOBASE_UF_SHOW_COUNTRY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N',
		),
		"SHOW_COUNTRY_CODE" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage("ALTASIB_GEOBASE_UF_SHOW_COUNTRY_CODE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N',
		),
		"SHOW_COORDINATES" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage("ALTASIB_GEOBASE_UF_SHOW_COORDINATES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'N',
		),
	)
);

if($arCurrentValues["FIND_NEAREST"] != "N")
{
	$arComponentParameters["PARAMETERS"]["NEAREST_MODE"] = array(
		"PARENT" => "NEAREST",
		"NAME" => GetMessage("ALTASIB_UF_NEAREST_MODE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"DEFAULT" => "all",
		"VALUES" => array(
			"geo" => GetMessage("ALTASIB_NMODE_GEO"),
			"region" => GetMessage("ALTASIB_NMODE_REGION"),
			"all" => GetMessage("ALTASIB_NMODE_ALL")
		),
	);
	$arComponentParameters["PARAMETERS"]["NEAREST_USER_CHOICE"] = array(
		"PARENT" => "NEAREST",
		"NAME" => GetMessage("ALTASIB_UF_NEAREST_USER_CHOICE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	);
}

?>