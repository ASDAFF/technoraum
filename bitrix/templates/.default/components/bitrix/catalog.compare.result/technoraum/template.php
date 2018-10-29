<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

if(count($arResult["ITEMS"]) < 2)
{
	?>
		<p>Необходимо добавить более 1 товара в список сравнения</p>
	<?
}
else
{
?>
<div class="glav_cat_wrap flexslider glav_cat_slider glav_cat_slider_comapare" id="glav_cat_slider_comapare">
	<ul class="slides">
		<?
			$i = 0;
			foreach($arResult["ITEMS"] as $item)
			{
				if($i == 0)
					echo "<li>";
				if($i == 2)
				{
					echo "</li><li>";
					$i = 0;
				}
				?>
				<div class="glav_cat_div">
					<div class="img">
						<em></em><img src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" alt="" />
						<a class="ref" href="<?=$item["DETAIL_PAGE_URL"]?>/"></a>
						<?
							$stick = $item["PROPERTIES"]["STICKER"]["VALUE_XML_ID"];
							switch($stick)
							{
								case "val1":
									$block = '<span class="hit">'.$item["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
									break;
								case "val2":
									$block = '<span class="new">'.$item["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
									break;
								case "val3":
									$block = '<span class="action">'.$item["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
									break;
								default:
									unset($block);
									break;
							}
							if($block)
								echo '<div class="flag">'.$block.'</div>';
						?>
					</div>
					<div class="text">
						<p class="title"><a href="<?=$item["DETAIL_PAGE_URL"]?>/"><?=$item["NAME"]?></a></p>
						<?
							if($item["PROPERTIES"]["ARTICLE"]["VALUE"])
							{
								?><p class="art">Артикул: <span><?=$item["PROPERTIES"]["ARTICLE"]["VALUE"]?></span></p><?
							}
							else
							{
						?><p class="art">Артикул: <span>-</span></p><?
							}
						?>
						<div class="the_price">
							<p class="old_price">
							<?
								if($item["PROPERTIES"]["OLD_PRICE"]["VALUE"] && $item["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"])
								{
									echo $item["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"]." &#8381;";
								}
							?>
							</p>
							<p class="price"><span><?=$item["PRICES"]["price"]["VALUE"]?></span><span> &#8381;</span></p>
						</div>
						<a class="button to_cart_button" href="<?=$item["BUY_URL"]?>">В корзине</a>
					</div>
					<a class="compare_x" href="?action=DELETE_FROM_COMPARE_LIST&id=<?=$item["ID"]?>"><span>Удалить из сравнения</span></a>
				</div>
				<?
				$i++;
			}
			echo "</li>";
		?>
	</ul>
</div>
<div class="inner_section">
	<div class="flexslider compare_table_slider" id="compare_table_slider">
		<ul class="slides">
		<?
			for($i=0;$i<=count($arResult["ITEMS"])-1;$i+=2)
			{
				?>
				<li>
					<div class="comapare_page_wrap clearfix">
						<div class="compare_table">
							<p class="table_title">Технические характеристики</p>
								<table>
									<?
										$j = 1;
										while($arResult["ITEMS"][$i]["PROPERTIES"]["DETAIL_P".$j])
										{
											?>
												<tr>
													<td><?=$arResult["ITEMS"][$i]["PROPERTIES"]["DETAIL_P".$j]["NAME"]?></td>
													<td>
														<?
															if($arResult["ITEMS"][$i]["PROPERTIES"]["DETAIL_P".$j]["VALUE"])
															{
																if($arResult["ITEMS"][$i]["PROPERTIES"]["DETAIL_P".$j]["VALUE"] == 1)
																	echo "<img src='".SITE_TEMPLATE_PATH."/img/green_check.png'>";
																else
																	echo $arResult["ITEMS"][$i]["PROPERTIES"]["DETAIL_P".$j]["VALUE"];
															}
															else
																echo " - ";
														?>
													</td>
													<td>
														<?
															if($arResult["ITEMS"][$i+1]["PROPERTIES"]["DETAIL_P".$j]["VALUE"])
															{
																if($arResult["ITEMS"][$i+1]["PROPERTIES"]["DETAIL_P".$j]["VALUE"] == 1)
																	echo "<img src='".SITE_TEMPLATE_PATH."/img/green_check.png'>";
																else
																	echo $arResult["ITEMS"][$i+1]["PROPERTIES"]["DETAIL_P".$j]["VALUE"];
															}
															else
																echo " - ";
														?>
													</td>
												</tr>
											<?
											$j++;
										}
									?>
								</table>
								<p class="table_title">Оснащение</p>
								<table>
									<?
										$j = 1;
										while($arResult["ITEMS"][$i]["PROPERTIES"]["COMP_P".$j])
										{
											?>
												<tr>
													<td><?=$arResult["ITEMS"][$i]["PROPERTIES"]["COMP_P".$j]["NAME"]?></td>
													<td>
														<?
															if($arResult["ITEMS"][$i]["PROPERTIES"]["COMP_P".$j]["VALUE"])
															{
																if($arResult["ITEMS"][$i]["PROPERTIES"]["COMP_P".$j]["VALUE"] == 1)
																	echo "<img src='".SITE_TEMPLATE_PATH."/img/green_check.png'>";
																else
																	echo $arResult["ITEMS"][$i]["PROPERTIES"]["COMP_P".$j]["VALUE"];
															}
															else
																echo " - ";
														?>
													</td>
													<td>
														<?
															if($arResult["ITEMS"][$i+1]["PROPERTIES"]["COMP_P".$j]["VALUE"])
															{
																if($arResult["ITEMS"][$i+1]["PROPERTIES"]["COMP_P".$j]["VALUE"] == 1)
																	echo "<img src='".SITE_TEMPLATE_PATH."/img/green_check.png'>";
																else
																	echo $arResult["ITEMS"][$i+1]["PROPERTIES"]["COMP_P".$j]["VALUE"];
															}
															else
																echo " - ";
														?>
													</td>
												</tr>
											<?
											$j++;
										}
									?>
								</table>
								<table>
									<tr class="bg_fff">
										<td><p class="table_title">Описание</p></td>
										<td><?=$arResult["ITEMS"][$i]["DETAIL_TEXT"]?></td>
										<td><?=$arResult["ITEMS"][$i+1]["DETAIL_TEXT"]?></td>
									</tr>										 
								</table>		
						</div>
					</div>
				</li>
				<?
			}
		?>
		</ul>	
	</div>	
</div>
<? } ?>