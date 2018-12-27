<?IncludeModuleLangFile(__FILE__);?>
<?

if (\Bitrix\Main\Loader::includeModule('sale')) {

	$cashboxResultDb = Bitrix\Sale\Cashbox\Internals\CashboxTable::getList(array(
	
		'select'  => array("ID", "HANDLER", "NAME"),
		'filter'  => array("HANDLER" => "ModuleCashboxCustom%")	
		
	));

	$_SESSION["CASHBOX_DELETE_ID"] = array();
	$cashBoxDeleteData = array();
	
	while($cashboxData = $cashboxResultDb->fetch())
	{
		$_SESSION["CASHBOX_DELETE_ID"][] = $cashboxData["ID"];
		$cashBoxDeleteData[]  = $cashboxData;
	}
	
}
?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
	<input type="hidden" name="step" value="2">
	<input type="hidden" name="id" value="modulpos.cashboxv4">
	<input type="hidden" name="uninstall" value="Y">
	<?if($_SESSION["CASHBOX_DELETE_ID"]):?>
	
		<?echo CAdminMessage::ShowMessage(GetMessage("modulpos.cashboxv4_MOD_UNINST_WARN"))?>
		<p><?echo GetMessage("modulpos.cashboxv4_MOD_UNINST_SAVE")?></p>
		<ul>
		<?foreach ($cashBoxDeleteData as $cashboxData):?>
		
			<li><?=$cashboxData["NAME"]?> (ID <?=$cashboxData["ID"]?>)</li>
			
		<?endforeach;?>
		</ul>
		
	<?else:?>
	
		<?echo CAdminMessage::ShowMessage(GetMessage("modulpos.cashboxv4_MOD_UNINST_CONFIRM"))?>
		
	<?endif;?>
	<input type="submit" name="inst" value="<?echo GetMessage("modulpos.cashboxv4_MOD_UNINST_DEL")?>">
</form>