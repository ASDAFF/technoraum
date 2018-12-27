<?
use Bitrix\Main\Localization;

class ModuleCashboxCustomV3 extends \Bitrix\Sale\Cashbox\CashboxAtolFarm
{

	const SERVICE_URL = 'https://service.modulpos.ru/api/fn/possystem/v3';
	const UUID_DELIMITER = '-';
	const TOKEN_OPTION_NAME = 'mdk_v3_';
	
    /**
	 * @return string 
	 */
	public static function getName()
	{
		return Localization\Loc::getMessage('SALE_MODULECASHBOX_TITLE_V3');
	}
	
	public static function buildUuid($type, $id)
	{
		$context = \Bitrix\Main\Application::getInstance()->getContext();
		$server = $context->getServer();
		$domain = $server->getServerName();
		$domain = str_replace(".", "-", $domain);
		
		return $type.static::UUID_DELIMITER.$domain.static::UUID_DELIMITER.$id;
	}
	
}
?>