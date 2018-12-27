<?
if(!check_bitrix_sessid()) return;

echo CAdminMessage::ShowNote(GetMessage("modulpos.cashboxv4_MOD_UNINST_OK"));

?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?echo LANG?>">
	<input type="submit" name="" value="<?echo GetMessage("modulpos.cashboxv4_MOD_BACK")?>">
<form>