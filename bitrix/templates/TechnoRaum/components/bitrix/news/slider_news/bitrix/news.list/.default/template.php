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
$this->setFrameMode(true);
?>
	<div class="glav_news_block flexslider glav_news_block_slider clearfix">
		<ul class="slides">
		<?
		foreach($arResult["ITEMS"] as $arItem)
		{
			if($arItem["ACTIVE_FROM"])
				$data = $arItem["ACTIVE_FROM"];
			else
				$data = $arItem["TIMESTAMP_X"];
			$data = explode(" ", $data);
			$data = $data[0];
			$data = explode("." , $data);
			$mon = $data[1];
			$mon = str_replace("0" , "" , $mon);
		
			if(in_array($data[2] , $years) == false)
				$years[$i] = $data[2];
			$m = array("������", "�������", "�����", "������", "���" ,"����" ,"����" ,"�������" ,"��������" ,"�������" ,"������" ,"�������");
			?>
				<li>
					<div class="glav_news_div">
						<input type="hidden" class="year" value="<?=$data[2]?>" />
						<input type="hidden" class="unit" value="<?=$arItem["PROPERTIES"]["UNIT"]["VALUE"]?>" />
						<div class="img">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="" /></a>
						</div>
						<div class="text">
							<?
								$unit = $arItem["PROPERTIES"]["UNIT"]["VALUE"];
								if($unit == "�������")
								{
									?><p class="type"><?=$arItem["PROPERTIES"]["UNIT"]["VALUE"]?></p><?
								}
								else
								{
									?><p class="type articles"><?=$arItem["PROPERTIES"]["UNIT"]["VALUE"]?></p><?
								}
							?>
							<p class="date"><?=$data[0]?> <?=$m[$mon-1]?> <?=$data[2]?></p>
							<p class="title">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
							</p>	
						</div>
					</div>
				</li>
			<?
			$i++;
		}
		?>
		</ul>
	</div>