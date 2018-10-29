<?
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	
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
        array("ID", "CALLBACK_FUNC", "MODULE", 
              "PRODUCT_ID", "QUANTITY", "DELAY", 
              "CAN_BUY", "PRICE", "WEIGHT")
    );
	while ($arItems = $dbBasketItems->Fetch())
	{
		if (strlen($arItems["CALLBACK_FUNC"]) > 0)
		{
			CSaleBasket::UpdatePrice($arItems["ID"], 
									 $arItems["CALLBACK_FUNC"], 
									 $arItems["MODULE"], 
									 $arItems["PRODUCT_ID"], 
									 $arItems["QUANTITY"]);
			$arItems = CSaleBasket::GetByID($arItems["ID"]);
		}

		$arBasketItems[] = $arItems;
	}

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
?>
<script src="/personal/order/make/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="/personal/order/make/js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>
<script src="/personal/order/make/js/step2_cdek.js" type="text/javascript"></script>
<style>.nstep{display:none}</style>

<div class="left_block step2_3">
	<label for="city"><p class="main_title">Где вы хотите получить заказ</p></label>
	<div class="ui-widget" style="display: inline-block;">
		<input type="text" placeholder="Населенный пункт" id="city" />
		<br />
	</div>
	<form id="cdek" method="POST">
		<input type="hidden" name="method" value="5" />
		<input type="hidden" name="use" value="1" />
		<input type="hidden" name="senderCityId" value="270"/>
		<input type="hidden" name="receiverCityId" id="receiverCityId" value=""/>
		<input type="hidden" name="tariffId" value="11"/>
		<input type="hidden" name="modeId" value="3"/> <!-- режим доставки, склад-дверь -->
		<input type="hidden" name="dateExecute" value="<?=$date["o"]."-".$date["m"]."-".$date["d"]?>"/> <!-- Дата доставки -->		
		<input type="hidden" name="weight" value="<?=$weight?>"/> <!-- Вес места, кг.--> 
		<input type="hidden" name="volume" value="<?=$size?>"/> <!-- объём места, длина*ширина*высота. -->
		<input style="margin-top:20px" type="submit" value="Посчитать">
	</form>
</div>
<div class="right_block step2_3">
	<div class="title"><p class="main_title">Вес и габариты</p></div>
	<div class="weight">Вес брутто заказ, кг: <span><?=number_format($weight, 1 , '.', '');?></span></div>
	<div class="size">Габариты, мм: <span><?=$length?> x <?=$width?> x <?=$height?></span></div>
</div>
<div style="clear:both;"></div>
<form id="dil_form" method="POST" action="/personal/order/make/">
	<div class="select_dil">
		<p class="main_title">Выберите транспортную компанию</p>
		<div class="row">
			<div class="col1"><div>транспортная компания</div></div>
			<div class="col2"><div>стоимость доставки</div></div>
			<div class="col3"><div>срок доставки</div></div>
		</div>
	</div>
	<div class="address_form">
		<p class="main_title">Адрес доставки</p>
		<input type="hidden" name="change_dmethod" value="3" />
		<input type="hidden" name="change_step" value="3" />
		
		<div class="row">
			<div class="title">Улица</div>
			<div><input type="text" name="address_p1" required/></div>
		</div>
		<div class="row cc">
			<div class="case">
				<div class="title">Дом</div>
				<input type="number" name="address_p2" required/>
			</div>
			<div class="case">
				<div class="title">Корпус</div>
				<input type="number" name="address_p3" required />
			</div>
			<div class="case">
				<div class="title">Кв. / офис</div>
				<input type="number" name="address_p4" required/>
			</div>
		</div>			
		<div class="row">
			<div class="title">Индекс</div>
			<input type="text" name="index" required/>
		</div>		
		<div class="row">
			<div class="title">Комментарий</div>
			<input type="text" name="index" />
		</div>
		<input type="submit" value="Перейти к способу оплаты" />
	</div>
</div>

	