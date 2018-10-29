<?
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	$order_error = 0;
	$_SESSION["paym"] = $_POST["paym"];
	
	CModule::IncludeModule("sale");
	CModule::IncludeModule("catalog");
	$arBasketItems = array();
	$dbBasketItems = CSaleBasket::GetList(
			array(
					"NAME" => "ASC",
					"ID" => "ASC"
				),
			array(
					"FUSER_ID" => CSaleBasket::GetBasketUserID(),
					"LID" => SITE_ID,
					"ORDER_ID" => "NULL"
				),
			false,
			false,
			array("PRODUCT_ID", "NAME" , "PRICE", "CURRENCY" , "QUANTITY")
		);
	while ($arItems = $dbBasketItems->Fetch())
	{
		if (strlen($arItems["CALLBACK_FUNC"]) > 0)
		{
			CSaleBasket::UpdatePrice
			(
				$arItems["PRODUCT_ID"],
				$arItems["NAME"],
				$arItems["PRICE"],
				$arItems["CURRENCY"],
				$arItems["QUANTITY"]
			);
			$arItems = CSaleBasket::GetByID($arItems["ID"]);
		}

		$arBasketItems[] = $arItems;
	} 
	global $USER;
	$_SESSION["user_cart"] = $arBasketItems;
	$_SESSION["user_id"] = $USER->GetID();

	$weight = 0;
	$width = 0;
	$height = 0;
	$length = 0;
	
	foreach($arBasketItems as $item)
	{
		$ID = $item["PRODUCT_ID"];
		$ar_res = CCatalogProduct::GetByID($ID);
		
		$weight += $ar_res["WEIGHT"] * $item["QUANTITY"];
		$width 	+= $ar_res["WIDTH"] * $item["QUANTITY"];
		$height += $ar_res["HEIGHT"] * $item["QUANTITY"];
		$length += $ar_res["LENGTH"] * $item["QUANTITY"];
	}
	$weight = $weight / 1000;
	$size = (($length / 10) * ($width / 10) * ($height / 10)) / 1000000;
	
	$basket_count = 0;
	$price = 0;
	foreach($arBasketItems as $item)
	{
		$basket_count += 1 * $item["QUANTITY"];
		$price += $item["PRICE"] * $item["QUANTITY"];
	}
	
	
		if($price > 10000)
			$discount = 3;
		if($price > 20000)
			$discount = 4;
		if($price > 30000)
			$discount = 5;
		if($price > 50000)
			$discount = 6;
		if($price > 100000)
			$discount = 7;
		if($price > 150000)
			$discount = 8;
		if($price > 200000)
			$discount = 9;
		if($price > 250000)
			$discount = 10;

	if($discount > $_SESSION["DISCOUNT"])
		$_SESSION["DISCOUNT"] = $discount;
	
	if($basket_count == 1)
		$line1 = $basket_count . " товар на сумму";
	elseif($basket_count < 5)
		$line1 = $basket_count . " товара на сумму";
	elseif($basket_count < 21)
		$line1 = $basket_count . " товаров на сумму";
	else
	{
		$last = substr($basket_count , strlen($basket_count)-1, 1);
		if($last == 1)
			$line1 = $basket_count . " товар на сумму";
		elseif($last < 5)
			$line1 = $basket_count . " товара на сумму";
		elseif($last < 10)
			$line1 = $basket_count . " товаров на сумму";
	}
	$line1 .= $price . " руб.";
	
	$d_summ = 0;
	switch($_POST["dmethod"])
	{
		case 1:
			$_SESSION["DELIVERY"] = 3;
			$line2 = "Самовывоз из магазина: ".$_POST["daddr"];
			break;
		case 2:
			$_SESSION["DELIVERY"] = 14;
			$line2 = "Самовывоз из пункта выдачи: ".$_POST["daddr"];

			include_once("CalculatePriceDeliveryCdek.php");
			try
			{
				$calc = new CalculatePriceDeliveryCdek();
				$calc->setSenderCityId('270');
				$calc->setReceiverCityId($_POST['city_id']);
				$calc->setDateExecute($date["o"]."-".$date["m"]."-".$date["d"]);
				$calc->setTariffId('11');
				$calc->setModeDeliveryId('3');
				$calc->addGoodsItemByVolume($weight, $size);
				
				if ($calc->calculate() === true) 
				{
					$res = $calc->getResult();
					$dil_price = $res['result']['price'];
				}
				else 
				{
					$err = $calc->getError();
					if( isset($err['error']) && !empty($err) ) 
					{
						echo "error|";
						foreach($err['error'] as $e) 
						{
							echo iconv("UTF-8" , "Windows-1251" , $e['code']) . "|" . iconv("UTF-8" , "Windows-1251" , $e['text']);
						}
					}
				}
			}catch (Exception $e) {
				echo 'Ошибка: ' . iconv("UTF-8" , "Windows-1251" , $e->getMessage()) . "<br />";
			}
			break;
		case 3:
			$_SESSION["DELIVERY"] = 16;
			$line2 = "Доставка до двери";
			break;
		default:
			echo "<p style='color:red;'>Ошибка. Не выбран способ доставки</p>";
			$order_error = 1;
			break;
	}
	
	if($_POST["paym"] == 1)
	{
		$_SESSION["PAYMENT"] = 5;
		$line3 = "Оплата наличными или банковской картой";
	}
	else
	{
		$_SESSION["PAYMENT"] = 8;
		$line3 = "Онлайн оплата";
	}
?>
<p class="main_title m">Данные заказа</p>
<div class="order_info">
	<div class="row">
		<div>Ваш заказ</div>
		<div><?=$line1?></div>
	</div>
	<? 
		if($dil_price)
		{
			?>
				<div class="row"><div>Сумма доставки</div><div><?=$dil_price?> руб.</div></div>
				<div class="row"><div>Итого</div><div><?=$price + $dil_price?> руб.</div></div>
			<?
		}
	?>
	<div class="row"><div>Способ получения</div><div><?=$line2?></div></div>
	<div class="row"><div>Способ оплаты</div><div><?=$line3?></div></div>
</div>
<p class="main_title m">Товары в заказе</p>
<div class="basket_items">
	<?
		foreach($arBasketItems as $item)
		{
			$data = CIBlockElement::GetProperty(8, $item["PRODUCT_ID"], array("sort" => "asc"), Array("CODE" => "OLD_PRICE"));
			$data = $data->Fetch();
			$img = CIBlockElement::GetByID($item["PRODUCT_ID"]);
			$img = $img->GetNext();
			$url = $img["DETAIL_PAGE_URL"];
			$img = CFile::ResizeImageGet($img["PREVIEW_PICTURE"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$img = $img["src"];
			
			
			$iprice = CPrice::GetBasePrice($item["PRODUCT_ID"]);
			$price_f = number_format($iprice["PRICE"] , 0 , " ", " ");
			?>
				<div class="row">
					<div class="img"><img src="<?=$img?>" /></div>
					<div class="name">
						<a href="<?=$url?>" target=_blank><?=$item["NAME"]?></a>
						<?
							$line = CIBlockElement::GetProperty(8, $item["PRODUCT_ID"], array("sort" => "asc"), Array("CODE" => "GIFT"));
							$data = $line->Fetch();
							
							if($data["VALUE"])
							{
								?>
									<div class="gift_block">
									<p>+ Подарки:</p>
									<ul>
									<?
										$res = CIBlockElement::GetByID($data["VALUE"]);
										if($ar_res = $res->GetNext())
										{
											$price = CPrice::GetBasePrice($data["VALUE"]);
											?><li><?=$ar_res["NAME"]?> - <?=$price?> &#8381;</li><?
										}
										while($data = $line->Fetch())
										{
											$res = CIBlockElement::GetByID($data["VALUE"]);
											if($ar_res = $res->GetNext())
											{
												$price = CPrice::GetBasePrice($data["VALUE"]);
												?><li><?=$ar_res["NAME"]?> - <?=$price?> &#8381;</li><?
											}
										}
									?>
									</ul>
									</div>
									<?
							}
						?>
					</div>
					<?
						$data = CIBlockElement::GetProperty(8, $item["PRODUCT_ID"], array("sort" => "asc"), Array("CODE" => "OLD_PRICE"));
						$data = $data->Fetch();
					?>
					<div class="discount">
						<?
							if(!$data["VALUE"])
							{
								if($_SESSION["DISCOUNT"])
								{
									echo $_SESSION["DISCOUNT"] . "%";
								}
							}
							else
								echo "-";
						?>
					</div>
					<div class="quantity"><?=$item["QUANTITY"]?> шт</div>
					<div class="price">
						<?
							if($data["VALUE"])
							{
								$data = CIBlockElement::GetProperty(8, $item["PRODUCT_ID"], array("sort" => "asc"), Array("CODE" => "OLD_PRICE_VAL"));
								$data = $data->Fetch();
								$data = number_format($data["VALUE"] , 0 , " " , " ");

								?>
									<p class="price"><?=$price_f?> руб.</p>
									<p class="old-price"><?=$data?> руб.</p>
								<?
							}
							else
							{
								if($_SESSION["DISCOUNT"])
								{
									$iprice = $iprice["PRICE"] - $iprice["PRICE"] * $_SESSION["DISCOUNT"] / 100;
									$price_f = number_format($iprice , 0 , " ", " ");
								}
								?><p class="price"><?=$price_f?> руб.</p><?
							}
						?>
					</div>
				</div>
			<?
		}
	?>
</div>
<p class="main_title m">Получатель заказа</p>
<form class="step_form" id="step4_form">
	<div class="tabs step4">
		<div data-id="1" class="tab1 tab active"><a tab-id="1">Контактные данные</a></div>
	</div>
	<div class="tabs_content client_info">	
		<div class="row">
			<div>ФИО</div>
			<div><?=iconv("UTF-8" , "Windows-1251" , $_SESSION["order_info"]["user"]["name"])?></div>
		</div>		
		<div class="row">
			<div>Телефон</div>
			<div><?=$_SESSION["order_info"]["user"]["phone"]?></div>
		</div>		
		<div class="row">
			<div>Электронная почта</div>
			<div><?=$USER->GetEmail()?></div>
		</div>
		<div class="comm">
			<div class="title">Примечание к заказу</div>
			<textarea class="comm" name="dtext"></textarea>
		</div>
		<div class="row">
			<button class="create_order">Подтвердить заказ</button>
		</div>
	</div>
</form>
