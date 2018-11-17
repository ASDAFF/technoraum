<?
class CDCMain
{
	const MODULE_ID = 'directcredit.frame';

	const STATUS_CANCEL = 'CANCEL';
	const STATUS_SIGN = 'SIGN';
		
	function CDCMain()
	{
	}
	
	function GetPaySystemsID()
	{
		$obPaySystems = CSalePaySystem::GetList(Array(),Array(),false,false,Array("ID","PSA_ACTION_FILE"));		
		$arResult = Array();
		while($arPaySystem = $obPaySystems->Fetch())
		{
			if(preg_match("#".self::MODULE_ID."#",$arPaySystem["PSA_ACTION_FILE"]))  $arResult[] = $arPaySystem["ID"];
		}
		
		return (empty($arResult)) ? false : $arResult;
	}
}
?>
