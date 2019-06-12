<?
use Bitrix\Main\Loader;

require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile( __FILE__ );

if($APPLICATION->GetGroupRight( "main" ) < "R")
	$APPLICATION->AuthForm( GetMessage( "ACCESS_DENIED" ) );

$module_id = "sotbit.seometa";
require_once ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/classes/general/CModuleOptions.php');
require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");

//file_put_contents(dirname(__FILE__).'/log_site.log', print_r($site, true));

if($REQUEST_METHOD == "POST" && strlen( $RestoreDefaults ) > 0 && check_bitrix_sessid())
{
	$CCSeoMeta = new CCSeoMeta();
	if (!$CCSeoMeta->getDemo())
		return false;

	COption::RemoveOption( $module_id );
	$z = CGroup::GetList( $v1 = "id", $v2 = "asc", array(
			"ACTIVE" => "Y",
			"ADMIN" => "N"
	) );
	while( $zr = $z->Fetch() )
		$APPLICATION->DelGroupRight( $module_id, array(
				$zr["ID"]
		) );
	if((strlen( $Apply )>0)||(strlen( $RestoreDefaults )>0))
		LocalRedirect( $APPLICATION->GetCurPage()."?lang=".LANGUAGE_ID."&mid=".urlencode( $mid )."&tabControl_active_tab=".urlencode( $_REQUEST["tabControl_active_tab"] )."&back_url_settings=".urlencode( $_REQUEST["back_url_settings"] ) );
	else
		LocalRedirect( $_REQUEST["back_url_settings"] );
}

$FilterType = array(
	"REFERENCE" => array(
		GetMessage($module_id.'_FILTER_TYPE_bitrix_chpu'),
		GetMessage($module_id.'_FILTER_TYPE_bitrix_not_chpu'),
		GetMessage($module_id.'_FILTER_TYPE_misshop_chpu'),
		GetMessage($module_id.'_FILTER_TYPE_combox_chpu'),
		GetMessage($module_id.'_FILTER_TYPE_combox_not_chpu')
	),
    "REFERENCE_ID" => array(
		"bitrix_chpu",
		"bitrix_not_chpu",
		"misshop_chpu",
		"combox_chpu",
		"combox_not_chpu"
	)
);

$arTabs = array(
	array(
		'DIV' => 'edit1',
		'TAB' => GetMessage( $module_id.'_edit1' ),
		'ICON' => '',
		'TITLE' => GetMessage( $module_id.'_edit1' ),
		'SORT' => '10'
	),
);

$arGroups = array(
	'GROUP_SETTINGS' => array(
		'TITLE' => GetMessage( $module_id.'_GROUP_SETTINGS' ),
		'TAB' => 0
	),
);

$arOptions = array(
	'FILTER_TYPE' => array(
		'GROUP' => 'GROUP_SETTINGS',
		'TITLE' => GetMessage( $module_id.'_FILTER_TYPE' ),
		'TYPE' => 'SELECT',
		'VALUES' => $FilterType,
		'DEFAULT' => 'bitrix_chpu',
		'REFRESH' => 'N',
		'SORT' => '1',
		'NOTES_ENUM' => GetMessage( $module_id.'_FILTER_TYPE_NOTE' ),
	),
	'NO_INDEX_'.$site => array(
		'GROUP' => 'GROUP_SETTINGS',
		'TITLE' => GetMessage( $module_id.'_NO_INDEX' ),
		'TYPE' => 'CHECKBOX',
		'REFRESH' => 'N',
		'SORT' => '5',
		'DEFAULT' => 'Y',
		'NOTES' => GetMessage( $module_id.'_NO_INDEX_NOTE' ),
	),
	'SOURCE_'.$site => array(
		'GROUP' => 'GROUP_SETTINGS',
		'TITLE' => GetMessage( $module_id.'_SOURCE' ),
		'TYPE' => 'TEXT',
		'REFRESH' => 'N',
		'SORT' => '10',
		'COLS' => 40,
		'ROWS' => 15,
		'DEFAULT' => "yandex.ru\ngoogle.ru\nwww.yahoo.com\nwww.rambler.ru",
		'NOTES' => GetMessage( $module_id.'_SOURCE_NOTE' ),
	),
	'PAGENAV_'.$site => array(
		'GROUP' => 'GROUP_SETTINGS',
		'TITLE' => GetMessage( $module_id.'_PAGENAV' ),
		'TYPE' => 'TEXT',
		'REFRESH' => 'N',
		'SORT' => '15',
		'COLS' => 40,
		'ROWS' => 1,
		'DEFAULT' => "",
		'NOTES' => GetMessage( $module_id.'_PAGENAV_NOTE' ),
	),
	'USE_CANONICAL_'.$site => array(
		'GROUP' => 'GROUP_SETTINGS',
		'TITLE' => GetMessage( $module_id.'_USE_CANONICAL' ),
		'TYPE' => 'CHECKBOX',
		'REFRESH' => 'N',
		'SORT' => '25',
		'DEFAULT' => 'Y',
	),
	'RETURN_AJAX_'.$site => array(
		'GROUP' => 'GROUP_SETTINGS',
		'TITLE' => GetMessage( $module_id.'_RETURN_AJAX' ),
		'TYPE' => 'CHECKBOX',
		'REFRESH' => 'N',
		'SORT' => '25',
		'DEFAULT' => 'N',
		'NOTES' => GetMessage( $module_id.'_RETURN_AJAX_NOTE' ),
	),
	'MANAGED_CACHE_ON' => array(
		'GROUP' => 'GROUP_SETTINGS',
		'TITLE' => GetMessage( $module_id.'_MANAGED_CACHE_ON' ),
		'TYPE' => 'CHECKBOX',
		'REFRESH' => 'N',
		'SORT' => '25',
		'DEFAULT' => 'N',
	),
);

$RIGHT = $APPLICATION->GetGroupRight( $module_id );
if($RIGHT != "D")
{
	$CCSeoMeta = new CCSeoMeta();
	if ($CCSeoMeta->ReturnDemo() == 2)
	{
		?>
		<div class="adm-info-message-wrap adm-info-message-red">
			<div class="adm-info-message">
				<div class="adm-info-message-title"><?=GetMessage("SEO_META_DEMO")?></div>
				<div class="adm-info-message-icon"></div>
			</div>
		</div>
		<?
	}
	if ($CCSeoMeta->ReturnDemo() == 3)
	{
	    ?>
	    <div class="adm-info-message-wrap adm-info-message-red">
			<div class="adm-info-message">
				<div class="adm-info-message-title"><?=GetMessage("SEO_META_DEMO_END")?></div>
				<div class="adm-info-message-icon"></div>
			</div>
	    </div>
		<?
	}

	$showRightsTab = false;
	$opt = new CModuleOptions( $module_id, $arTabs, $arGroups, $arOptions, $showRightsTab );
	$opt->ShowHTML();
}
$APPLICATION->SetTitle( GetMessage( $module_id.'_TITLE' ) );
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
