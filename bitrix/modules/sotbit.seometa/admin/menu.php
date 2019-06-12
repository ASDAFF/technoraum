<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$iModuleID = "sotbit.seometa";

if($APPLICATION->GetGroupRight($iModuleID) != "D")
{
    $rsSites = CSite::GetList($by = "sort", $order = "desc", array("ACTIVE" => "Y"));
    while($arSite = $rsSites->Fetch())
	{
        $Sites[] = $arSite;
	}
	unset($rsSites);
	unset($arSite);

	$Paths = array('settings' => '.php');
	if(count($Sites) == 1) // if one site
	{
        foreach($Paths as $key => $Path)
        {
            $Settings[$key] = array(
                "text" => Loc::getMessage("MENU_SEOMETA_".$key."_SETTINGS_TEXT"),
				"url" => "sotbit.seometa_".$key.$Path."?lang=".LANGUAGE_ID.'&site='.$Sites[0]['LID'],
				"title" => Loc::getMessage("MENU_SEOMETA_".$key."_SETTINGS_TEXT")
            );
        }
	}
	else
	{
        $Items = array();
		foreach($Paths as $key => $Path)
		{
			foreach($Sites as $Site)
			{
				$Items[$key][] = array(
                    "text" => '['.$Site['LID'].'] '.$Site['NAME'],
					"url" => "sotbit.seometa_".$key.$Path."?lang=".LANGUAGE_ID.'&site='.$Site['LID'],
					"title" => $Site['NAME']
				);
			}
		}

        foreach($Paths as $key => $Path)
        {
            $Settings[$key] = array(
                "text" => Loc::getMessage("MENU_SEOMETA_".$key."_SETTINGS_TEXT"),
                "items_id" => "menu_sotbit.seometa_settings".$key,
                "items" => $Items[$key],
                "title" => Loc::getMessage("MENU_SEOMETA_".$key."_SETTINGS_TEXT")
            );
        }
	}

	$parent = 'global_menu_marketing';
	if(\Bitrix\Main\Loader::includeModule('sotbit.missshop'))
	{
		$parent = 'global_menu_missshop';
	}
	if(\Bitrix\Main\Loader::includeModule('sotbit.mistershop'))
	{
		$parent = 'global_menu_mistershop';
	}
	if(\Bitrix\Main\Loader::includeModule('sotbit.b2bshop'))
	{
		$parent = 'global_menu_b2bshop';
	}

    $aMenu = array(
        "parent_menu" => $parent,
        "section" => 'sotbit.seometa',
        "sort" => 2000,
        "text" => Loc::getMessage("MENU_SEOMETA_TEXT"),
        "title" => Loc::getMessage("MENU_SEOMETA_TITLE"),
        "url" => "sotbit.seometa_list.php?lang=" . LANGUAGE_ID,
        "icon" => "seometa_menu_icon",
        "page_icon" => "seometa_page_icon",
        "items_id" => "menu_sotbit.seometa",
        "items" => array(
            array(
                "text" => Loc::getMessage("MENU_SEOMETA_LIST_OF_CONDITIONS_TEXT"),
                "url" => "sotbit.seometa_list.php?lang=" . LANGUAGE_ID,
                "more_url" => array(
                    "sotbit.seometa_list.php",
                    "sotbit.seometa_edit.php",
                    "sotbit.seometa_section_edit.php"
                ),
                "title" => Loc::getMessage("MENU_SEOMETA_LIST_OF_CONDITIONS_TITLE")
            ),
            array(
                "text" => Loc::getMessage("MENU_SEOMETA_AUTOGENERATION_OF_CONDITIONS_TEXT"),
                "url" => "sotbit.seometa_autogeneration_list.php?lang=" . LANGUAGE_ID,
                "more_url" => array(
                    "sotbit.seometa_autogeneration_list.php",
                    "sotbit.seometa_autogeneration_edit.php"
                ),
                "title" => Loc::getMessage("MENU_SEOMETA_AUTOGENERATION_OF_CONDITIONS_TITLE")
            ),
            array(
                "text" => Loc::getMessage("MENU_SEOMETA_SITEMAP_GENERATION_TEXT"),
                "url" => "sotbit.seometa_sitemap_list.php?lang=" . LANGUAGE_ID,
                "more_url" => array(
                    "sotbit.seometa_sitemap_list.php",
                    "sotbit.seometa_sitemap_edit.php"
                ),
                "title" => Loc::getMessage("MENU_SEOMETA_SITEMAP_GENERATION_TITLE")
            ),
            array(
                "text" => Loc::getMessage("MENU_SEOMETA_CHPU_LIST_TEXT"),
                "url" => "sotbit.seometa_chpu_list.php?lang=" . LANGUAGE_ID,
                "more_url" => array(
                    "sotbit.seometa_chpu_list.php",
                    "sotbit.seometa_chpu_edit.php",
                    "sotbit.seometa_section_chpu_edit.php"
                ),
                "title" => Loc::getMessage("MENU_SEOMETA_CHPU_LIST_TITLE")
            ),
            array(
                "text" => Loc::getMessage("MENU_SEOMETA_WEBMASTER_TEXT"),
                "url" => "sotbit.seometa_webmaster_list.php?lang=" . LANGUAGE_ID,
                "more_url" => array(
                    "sotbit.seometa_webmaster_list.php",
                    "sotbit.seometa_webmaster_edit.php"
                ),
                "title" => Loc::getMessage("MENU_SEOMETA_WEBMASTER_TITLE")
            ),
            array(
                "text" => Loc::getMessage("MENU_SEOMETA_STATISTICS_TEXT"),
                "title" => Loc::getMessage("MENU_SEOMETA_STATISTICS_TITLE"),
                "dynamic" => true,
                "items_id" => "menu_sotbit.seometa.statistics",
                "items" => array(
                    array(
                        "items_id" => "menu_sotbit.seometa.statistics",
                        "text" => Loc::getMessage("MENU_SEOMETA_STATISTICS_GRAPHS_TITLE"),
                        "url" => "sotbit.seometa_stat_graph.php?lang=" . LANGUAGE_ID,
                        "title" => Loc::getMessage("MENU_SEOMETA_STATISTICS_GRAPH_TITLE")
                    ),
                    array(
                        "items_id" => "menu_sotbit.seometa.statistics",
                        "text" => Loc::getMessage("MENU_SEOMETA_STATISTICS_LIST_TITLE"),
                        "url" => "sotbit.seometa_stat_list.php?lang=" . LANGUAGE_ID,
                        "title" => Loc::getMessage("MENU_SEOMETA_STATISTICS_LIST_TITLE")
                    ),
                ),
            ),
            array(
				"text" => Loc::getMessage("MENU_SEOMETA_SETTINGS_TEXT"),
				"title" => Loc::getMessage("MENU_SEOMETA_SETTINGS_TEXT"),
				"dynamic" => true,
				"items_id" => "menu_sotbit.seometa.settings",
				"items" => array($Settings['settings'])
			),
        )
    );

    return $aMenu;
}

return false;
