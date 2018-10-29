<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница не найдена");
?>
<style>
	h1:first-child{display: none;}
	@media(max-width: 700px){.er404{font-size:100px !important;}}
</style>
<p class="er404" style="line-height:initial;font-size: 250px;text-align:center;color: #feee35;font-weight: bold;text-shadow: 0 0 5px black;">404</p>
<br>
	<h1>Страница не найдена</h1>
	<div class="bx-404-container">
		<div class="bx-404-text-block">Неправильно набран адрес, <br>или такой страницы на сайте больше не существует.</div>
		<div class="">Вернитесь на <a href="<?=SITE_DIR?>">главную</a> или воспользуйтесь поиском.</div>
	</div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>