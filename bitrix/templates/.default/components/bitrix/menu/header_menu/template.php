<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
		die();
	$sub2_index = 1;
?>


<nav class="header_menu">
	<ul class="clearfix">
		<?
			foreach($arResult as $key=>$item)
			{
				if($item["DEPTH_LEVEL"] != 1)
					continue;
				?>
					<li <? if($item["IS_PARENT"]) echo 'class="has_ul"'?>>
						<a href="<?=$item["LINK"]?>"><?=$item["TEXT"]?></a>
						<?
							if($item["IS_PARENT"])
							{
								?>
									<div class="header_menu_dropdown">
										<div class="inner_section">
											<ul>
												<?
													foreach($arResult as $sub_key=>$sub_item)
													{
														if($sub_key > $key)
														{
															if($sub_item["DEPTH_LEVEL"] == 2)
															{
																?>
																	<li>
																		<a href="<?=$sub_item["LINK"]?>" class="opensub" data-index="<?=$sub2_index?>"><?=$sub_item["TEXT"]?></a>
																			<?
																				if($sub_item["IS_PARENT"])
																				{
																					$sub2_level[$sub2_index] .= '<div data-index="'.$sub2_index.'" class="header_menu_dropdown_level2';

																					$sub2_level[$sub2_index] .= '"><ul>';

																					$index = 0;
																					foreach($arResult as $sub2_key=>$sub2_item)
																					{
																						if($sub2_key > $sub_key)
																						{
																							if($sub2_item["DEPTH_LEVEL"] == 3)
																							{
																								if($index == 8)
																								{
																									$sub2_level[$sub2_index] .= '</ul><ul>';
																									$index = 0;
																								}
																								$sub2_level[$sub2_index] .= '<li><a href="'.$sub2_item["LINK"].'">'.$sub2_item["TEXT"].'</a></li>';
																								$index++;
																							}
																							elseif($sub2_item["DEPTH_LEVEL"] < 3)
																								break;
																						}
																					}
																					$sub2_level[$sub2_index] .= '</ul></div>';
																				}
																			?>
																	</li>
																<?
																$sub2_index++;
															}
															elseif($sub_item["DEPTH_LEVEL"] < 2)
															{
																break;
															}

														}
													}
												?>
											</ul>
											<ul>
												<?
													foreach($sub2_level as $lvl)
														echo $lvl;
												?>
											</ul>
										</div>
									</div>
								<?
							}
						?>
					</li>
				<?
			}
		?>
	</ul>
</nav>