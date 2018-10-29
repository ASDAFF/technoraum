<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Дисконтная система");
?>
<div class="discont_page_wrap clearfix">
	<div class="discont_page_left_column">
		<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/discont_text.php",Array(),Array("MODE"=>"html")); ?>
		<div class="yellow_text_block">
			<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/discont_yellow.php",Array(),Array("MODE"=>"html")); ?>
		</div>
		<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/discont_price.php",Array(),Array("MODE"=>"html")); ?>
	</div>
	<div class="discont_page_right_column">
		<div class="discont_card">
			<div class="discont_card_inner">
				<span class="card_code">003466</span>
				<p class="title">TechnoRaum</p>
				<p class="grey">Моечная и уборочная техника из Германии </p>
				<p class="subtitle">Магазины</p>
				<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/discont_shops.php",Array(),Array("MODE"=>"html")); ?>
				<p class="subtitle">Сервисный центр</p>
				<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/discont_service.php",Array(),Array("MODE"=>"html")); ?>
			</div>
			<div class="discount_bubbles">
				<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/discont_card_percent.php",Array(),Array("MODE"=>"html")); ?>
			</div>
		</div>
		<? $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/include/discont_card_text.php",Array(),Array("MODE"=>"html")); ?>
	</div>							
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>