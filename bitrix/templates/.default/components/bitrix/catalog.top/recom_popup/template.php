<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogTopComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

					$i = 0;
					foreach($arResult["ITEMS"] as $item)
					{
						if($i == 5)
							break;
						?>
							<div class="row">
								<div class="img"><img src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" /></div>
								<div class="name"><a href="<?=$item["DETAIL_PAGE_URL"]?>"><?=$item["NAME"]?></a></div>
								<div class="price"><?=number_format($item["PRICES"]["price"]["VALUE"], 0, ',', ' ')?> Р</div>
								<div class="quantity">
									<button class="minus">-</button>
									<div class="count">
										<input type="number" min="1" max="99" name="quantity" value="1"/>
									</div>
									<button class="plus">+</button>
								</div>
								<div class="btn"><button data-href="<?=$item["BUY_URL"]?>" class="popup_buy_btn">Добавить к корзину</button></div>
							</div>		
						<?
						$i++;
					}
?>