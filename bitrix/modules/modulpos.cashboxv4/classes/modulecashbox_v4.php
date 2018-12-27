<?
use Bitrix\Main\Localization;

class ModuleCashboxCustomV4 extends \Bitrix\Sale\Cashbox\CashboxAtolFarmV4
{

	const SERVICE_URL = 'https://service.modulpos.ru/api/fn/possystem/v4';
	const UUID_DELIMITER = '-';
	const TOKEN_OPTION_NAME = 'mdk_v4_';
	
    /**
	 * @return string 
	 */
	public static function getName()
	{
		return Localization\Loc::getMessage('SALE_MODULECASHBOX_TITLE_V4');
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