<?
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NO_AGENT_CHECK', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);

$mid = "altasib.geobase";
$incMod = CModule::IncludeModuleEx($mid);

$popupWin = new CJSPopup(GetMessage("ALTASIB_GEOBASE_FORM_TITLE"), array("SUFFIX"=>($_GET['subdialog'] == 'Y'? 'subdialog':'')));

if(intval($_POST['ID']) > 0)
{
	$rsCity = CAltasibGeoBaseAllSelected::GetByID($_POST['ID']);
	$arCity = $rsCity->Fetch();
}

if(!isset($_REQUEST["save"]) || strlen($_REQUEST["save"]) <= 0)
{
	$sDescr = GetMessage("ALTASIB_GEOBASE_DESCR_EDIT");
}

$popupWin->ShowTitlebar(GetMessage("ALTASIB_GEOBASE_FORM_TITLE"));

if(!isset($_REQUEST["save"]) || strlen($_REQUEST["save"]) <= 0):

	// -- If not saving

	$popupWin->StartDescription("bx-property-page");

	if($strWarning != '')
		$popupWin->ShowValidationError($strWarning);

	$arSelC = CAltasibGeoBaseSelected::GetCityByID($ID, array());
	?><p><?=$sDescr?> #<?=$ID?> &nbsp;&nbsp;<span title="code <?=htmlspecialcharsbx($arSelC["CODE"])?>"><?=htmlspecialcharsbx($arSelC["SOCR"]).".&nbsp;"?><b><?=htmlspecialcharsbx($arSelC["NAME"])?></b></span> <?=$strActID?></p><?

	$popupWin->EndDescription();
	$popupWin->StartContent();
?>
<tr><td colspan="2">
	<div style='background-color:#fff;padding:0;border-top:1px solid #8E8E8E;border-bottom:1px solid #8E8E8E;margin-bottom:15px;'>
		<div style='background-color:#8E8E8E;height:30px;padding:7px;border:1px solid #fff'>
			<a href='http://www.is-market.ru?param=cl' target='_blank'>
				<img src='/bitrix/images/altasib.geobase/is-market.gif' style='float:left;margin-right:15px;' border='0' />
			</a>
			<div style='margin:13px 0px 0px 0px'>
				<a href='http://www.is-market.ru?param=cl' target='_blank' style='color:#fff;font-size:10px;text-decoration:none'><?=GetMessage('ALTASIB_IS')?></a>
			</div>
		</div>
	</div>
</td></tr>
<?

if($incMod == '0')
	CAdminMessage::ShowMessage(Array("MESSAGE"=>GetMessage("ALTASIB_GEOBASE_NF", Array("#MODULE#" => $mid)), "HTML"=>true, "TYPE"=>"ERROR"));
elseif($incMod == '2')
{
	?><span class="errortext"><?=GetMessage("ALTASIB_GEOBASE_DEMO_MODE", Array("#MODULE#" => $mid))?></span><br/><?
}
elseif($incMod == '3')
	CAdminMessage::ShowMessage(Array("MESSAGE"=>GetMessage("ALTASIB_GEOBASE_DEMO_EXPIRED", Array("#MODULE#" => $mid)), "HTML"=>true, "TYPE"=>"ERROR"));


if($incMod == '0' || $incMod == '3')
	return false;
?>

<table class="bx-width100" id="bx_page_properties">

	<tr>
		<td width="40%"><?=GetMessage("ALTASIB_GEOBASE_SORT")?></td>
		<td width="60%">
			<input name="FIELD_SORT" size="20" value="<?
				if(!empty($arCity["SORT"])):
					echo $arCity["SORT"];
				endif;
				?>" type="text">
		</td>
	</tr>
<?
	echo CAltasibGeoBaseSelected::GetTabs();
?>
</table>

<input type="hidden" name="ID" value="<?=$_REQUEST["ID"]?>"/>

<input type="hidden" name="save" value="Y" />
<?

$popupWin->EndContent();
$popupWin->ShowStandardButtons();

// -- End not saving

else:
	CUtil::JSPostUnescape();
	$popupWin->StartContent();

	if(intval($_POST['ID']) > 0)
	{
		CAltasibGeoBaseSelected::SetValues($_POST['ID']);

		if(intval($_POST['FIELD_SORT']) > 0 && $_POST['FIELD_SORT'] != $arCity["SORT"])
		{
			$reSort = CAltasibGeoBaseAllSelected::ChangeSortCity($_POST['ID'], $_POST['FIELD_SORT']);
		}
	}

	$popupWin->EndContent();
	$popupWin->ShowStandardButtons(array("close"));
	echo '<script>'
			.'top.BX.showWait(); '
			.'setTimeout(function() {'
				.'top.'.$popupWin->jsPopup.'.Close(); '
				.' if(typeof altasib_geobase_update_table != "undefined") altasib_geobase_update_table();'
				.'top.BX.closeWait();'
			.'}, 1700);'
		.'</script>';

endif;
