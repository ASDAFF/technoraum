<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Оформление заказа");
	global $USER;


	$steps = array("Контактные данные" , "Получение", "Оплата", "Подтвеждение заказа");
	
	if($_GET["step"] == 2)
		$_SESSION["order_step"] = 2;
	
	if(!$_SESSION["order_step"])
	{
		if($USER->IsAuthorized())
			$_SESSION["order_step"] = 2;
		else
			$_SESSION["order_step"] = 1;
	}
	if($_POST["change_step"])
		$_SESSION["order_step"] = $_POST["change_step"];

	if(!$USER->IsAuthorized())
		$_SESSION["order_step"] = 1;
?>
	<link rel="stylesheet" type="text/css" href="/personal/order/make/js/style.css"></link>
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru-RU" type="text/javascript"></script>
	<script src="/personal/order/make/js/order.js" type="text/javascript"></script>
	<div class="steps">
		<?
			for($i=1;$i<=count($steps);$i++)
			{
				?>
					<div class="step <? if($i == $_SESSION["order_step"]) echo "active"; ?>">
						<div class="content">
							<div class="num <? if($i < $_SESSION["order_step"] && $_SESSION["order_step"] > 1) echo 'green'?>">
							<?
								if($i < $_SESSION["order_step"] && $_SESSION["order_step"] > 1)
								{
									?><img src="/personal/order/make/img/ok.png" /><?
								}
								else
								{
									?><span><?=$i?></span><?
								}
							?>
							</div>
							<div class="title">
							<?
								if($i < $_SESSION["order_step"])
								{
									?>
										<a class="check-step" data-id="<?=$i?>" href="<?=$_SERVER["REQUEST_URI"]?>"><?=$steps[$i-1]?></a>
									<?
								}
								else
									echo $steps[$i-1];
							?>
							</div>
						</div>
					</div>
				<?
			}
		?>
	</div>
	<div class="order_container"><?require_once("include/step".$_SESSION["order_step"].".php");?></div>
	<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>