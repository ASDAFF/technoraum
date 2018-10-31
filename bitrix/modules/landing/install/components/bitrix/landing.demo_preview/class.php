<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

\CBitrixComponent::includeComponentClass('bitrix:landing.demo');

class LandingSiteDemoPreviewComponent extends LandingSiteDemoComponent
{
	/**
	 * Base executable method.
	 * @return void
	 */
	public function executeComponent()
	{
		$init = $this->init();

		if ($init)
		{
			$this->checkParam('SITE_ID', 0);
			$this->checkParam('CODE', '');
			$this->checkParam('TYPE', '');
			$this->checkParam('PAGE_URL_BACK', '');
			$this->checkParam('SITE_WORK_MODE', 'N');

			$code = $this->arParams['CODE'];
			$demo = $this->getDemoPage($code);
			if (isset($demo[$code]))
			{
				if ($demo[$code]['REST'] > 0)
				{
					$demo[$code]['DATA'] = $this->getTemplateManifest(
						$demo[$code]['REST']
					);
				}
				$this->arResult['COLORS'] = \Bitrix\Landing\Hook\Page\Theme::getColorCodes();
				$this->arResult['TEMPLATE'] = $demo[$code];
				$this->arResult['TEMPLATE']['URL_PREVIEW'] = $this->getUrlPreview($code);

//				match COLOR THEME
//				get from page
				if (!($themeCurr = $this->arResult['TEMPLATE']['DATA']['fields']['ADDITIONAL_FIELDS']['THEME_CODE']))
				{
//					if note set page theme - get from site
					$siteCode = $demo[$code]['DATA']['parent'] ? $demo[$code]['DATA']['parent'] : $demo[$code]['id'];
//					todo: cant use PARENT in one-pages templates. Need another
					$demoSite = $this->getDemoSite()[$siteCode];
					if (!($themeCurr = $demoSite['DATA']['fields']['ADDITIONAL_FIELDS']['THEME_CODE']))
					{
//						of get first element from theme list
						$themeCurr = array_shift(array_keys($this->arResult['COLORS']));
					}
				}

//				need add current theme to base-list
				$this->arResult['COLOR_CURRENT'] = $themeCurr;
				if (isset($this->arResult['COLORS'][$themeCurr]))
				{
					$this->arResult['COLORS'][$themeCurr]['base'] = true;
				}
			}
			else
			{
				$this->arResult['COLORS'] = array();
				$this->arResult['TEMPLATE'] = array();
			}
		}

		parent::executeComponent();
	}
}