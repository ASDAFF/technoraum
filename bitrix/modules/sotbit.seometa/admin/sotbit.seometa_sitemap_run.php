<?
require_once ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/xml.php');
use Bitrix\Main\Localization\Loc;
use Sotbit\Seometa\SitemapTable;
use Sotbit\Seometa\ConditionTable;
use Sotbit\Seometa\SeometaUrlTable;
use Bitrix\Main\Loader;
Loc::loadMessages( __FILE__ );

if (!$USER->CanDoOperation( 'sotbit.seometa' ))
{
    $APPLICATION->AuthForm( Loc::getMessage( "ACCESS_DENIED" ) );
}

Loader::includeModule( 'sotbit.seometa' );
$ID = intval( $_REQUEST['ID'] );
$arSitemap = null;

if ($ID > 0)
{
    $dbSitemap = SitemapTable::getById( $ID );
    $arSitemap = $dbSitemap->fetch();
}

if (!is_array( $arSitemap ))
{
    require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
    ShowError( Loc::getMessage( "SEO_META_ERROR_SITEMAP_NOT_FOUND" ) );
    require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
}
else
{
    $arSitemap['SETTINGS'] = unserialize( $arSitemap['SETTINGS'] );
}

$arSites = array();
$rsSites = CSite::GetById( $arSitemap['SITE_ID'] );
$arSite = $rsSites->Fetch();
$SiteUrl = "";

if (isset( $arSitemap['SETTINGS']['PROTO'] ) && $arSitemap['SETTINGS']['PROTO'] == 1)
{
    $SiteUrl .= 'https://';
}
elseif (isset( $arSitemap['SETTINGS']['PROTO'] ) && $arSitemap['SETTINGS']['PROTO'] == 0)
{
    $SiteUrl .= 'http://';
}
else
{
    require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
    ShowError( Loc::getMessage( "SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND" ) );
    require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
}

if (isset( $arSitemap['SETTINGS']['DOMAIN'] ) && !empty( $arSitemap['SETTINGS']['DOMAIN'] ))
    $SiteUrl .= $arSitemap['SETTINGS']['DOMAIN'];
else
{
    ShowError( Loc::getMessage( "SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND" ) );
}

if (isset( $arSitemap['SETTINGS']['FILENAME_INDEX'] ) && !empty( $arSitemap['SETTINGS']['FILENAME_INDEX'] ))
    $SiteMapUrl = $SiteUrl . '/' . $arSitemap['SETTINGS']['FILENAME_INDEX'];
else
{
    ShowError( Loc::getMessage( "SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND" ) );
}

if (isset( $arSitemap['SETTINGS']['FILTER_TYPE'] ) && !is_null( $arSitemap['SETTINGS']['FILTER_TYPE'] ))
{
    $FilterTypeKey = key( $arSitemap['SETTINGS']['FILTER_TYPE'] );
    $FilterCHPU = $arSitemap['SETTINGS']['FILTER_TYPE'][$FilterTypeKey];

    $FilterType = strtolower($FilterTypeKey.((!$FilterCHPU)?'_not':'').'_chpu');
}
else
{
    ShowError( Loc::getMessage( "SEO_META_ERROR_SITEMAP_FILTER_TYPE_NOT_FOUND" ) );
}

if (file_exists( $arSite['ABS_DOC_ROOT'] . $arSite['DIR'] . $arSitemap['SETTINGS']['FILENAME_INDEX'] ))
{
    $FoundSeoMetaSitemap = false;
    //$xml = simplexml_load_file( $arSite['ABS_DOC_ROOT'] . $arSite['DIR'] . 'sitemap.xml' );
    $xml = simplexml_load_file( $arSite['ABS_DOC_ROOT'] . $arSite['DIR'] . $arSitemap['SETTINGS']['FILENAME_INDEX'] );

    foreach ( $xml->sitemap as $sitemap ) // search if exist seometa sitemap in main sitemap
    {
        if (isset( $sitemap->loc ) && $sitemap->loc == $SiteUrl . '/sitemap_seometa_' . $ID . '.xml')
            $FoundSeoMetaSitemap = true;
    }

    if (!$FoundSeoMetaSitemap) // IF seometa sitemap not found add main sitemap
    {
        $NewSitemap = $xml->addChild( "sitemap" );
        $NewSitemap->addChild( "loc", $SiteUrl . '/sitemap_seometa_' . $ID . '.xml' );
        $NewSitemap->addChild( "lastmod", (isset($arSitemap['DATE_RUN']) && !empty($arSitemap['DATE_RUN']))?str_replace( ' ', 'T', date( 'Y-m-d H:i:sP', strtotime( $arSitemap['DATE_RUN'] ) ) ):str_replace( ' ', 'T', date( 'Y-m-d H:i:sP', strtotime( $arSitemap['TIMESTAMP_CHANGE'] ) ) ) );
        //file_put_contents( $arSite['ABS_DOC_ROOT'] . $arSite['DIR'] . 'sitemap.xml', $xml->asXML() );
        file_put_contents( $arSite['ABS_DOC_ROOT'] . $arSite['DIR'] . $arSitemap['SETTINGS']['FILENAME_INDEX'], $xml->asXML() );
    }

    // START GENERATE XML ARRAY
    $rsCondition = ConditionTable::getList( array(
        'select' => array(
            'ID',
            'DATE_CHANGE',
            'INFOBLOCK',
            'STRONG',
            'NO_INDEX',
            'RULE',
            'SITES',
            'SECTIONS',
            'PRIORITY',
            'CHANGEFREQ',
        ),
        'filter' => array(
            'ACTIVE' => 'Y',
            '!=NO_INDEX' => 'Y',
        ),
        'order' => array(
            'ID' => 'asc'
        )
    ) );

    $writer = \Sotbit\Seometa\Link\XmlWriter::getInstance($ID, $arSite['ABS_DOC_ROOT'] . $arSite['DIR'], $SiteUrl);

    $CONDS = array(); // Array for XML content
    while($arCondition = $rsCondition->Fetch())
    {
        $rule = unserialize($arCondition['RULE']);
        if(empty($rule['CHILDREN']))
            continue;

        // reset all 'IN_SITEMAP' statuses before new generation of sitemap
        $allInSitemapStatuses = SeometaUrlTable::getInSitemapStatuses($arCondition['ID']);
        if($allInSitemapStatuses)
        {
            foreach($allInSitemapStatuses as $item)
                SeometaUrlTable::update($item['ID'], array('IN_SITEMAP' => 'N'));
        }

        $link = \Sotbit\Seometa\Helper\Link::getInstance();
        $link->Generate($arCondition['ID'],  $writer);


        // если включена опция перегенерации
        //$writerForRegenerate = \Sotbit\Seometa\Link\ChpuWriter::getWriterForSitemap($arCondition['ID']);
        //$link->Generate($arCondition['ID'], $writerForRegenerate);
    }

    $writer->WriteEnd();

    SitemapTable::update($ID, array('DATE_RUN' => new Bitrix\Main\Type\DateTime()));
?>
    <script>
        top.BX.finishSitemap();
    </script>
<?
}
else
{
    ShowError( Loc::getMessage( "SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND" ) . ' ' . $arSite['ABS_DOC_ROOT'] . $arSite['DIR'] . $arSitemap['SETTINGS']['FILENAME_INDEX'] );
}
?>