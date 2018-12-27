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
?><div class="glav_news_block clearfix"><?
$i = 0;
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
	if(substr($mon , 0 , 1) == "0")
	$mon = str_replace("0" , "" , $mon);

	if(in_array($data[2] , $years) == false)
		$years[$i] = $data[2];
	$m = array("Января", "Февраля", "Марта", "Апреля", "Мая" ,"Июня" ,"Июля" ,"Августа" ,"Сентября" ,"Октября" ,"Ноября" ,"Декабря");
	?>
		<div class="glav_news_div action_block">
			<input type="hidden" class="year" value="<?=$data[2]?>" />
			<input type="hidden" class="unit" value="<?=$arItem["PROPERTIES"]["UNIT"]["VALUE"]?>" />
			<div class="img">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="" /></a>
			</div>
			<div class="text">
				<p class="date"><?=$data[0]?> <?=$m[$mon-1]?> <?=$data[2]?></p>
				<p class="title">
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
				</p>	
			</div>
		</div>
	<?
	$i++;
}
?></div><?
asort($years);
for($i=0;$i<=count($years);$i++)
{
	if($years[$i])
	{
		?>
			<script>
				$("select[name='news_display_time']").append("<option value='<?=$years[$i]?>'><?=$years[$i]?></option>");
			</script>
		<?	
	}
}
if($arParams["DISPLAY_BOTTOM_PAGER"])
	echo "<div class='pagination'>".$arResult["NAV_STRING"]."</div>";
?>