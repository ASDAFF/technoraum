<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");

if($_GET["form"] == 1)
{
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
	$basket = Bitrix\Sale\Basket::create("s1");

	foreach ($arBasketItems as $product)
		{
			$item = $basket->createItem("catalog", $product["PRODUCT_ID"]);
			unset($product["PRODUCT_ID"]);
			$item->setFields($product);
		}
	$order = Bitrix\Sale\Order::create("s1", $USER->GetID());
	$order->setPersonTypeId(1);
	$order->setBasket($basket);
	$shipmentCollection = $order->getShipmentCollection();
	$shipment = $shipmentCollection->createItem(
			Bitrix\Sale\Delivery\Services\Manager::getObjectById($_POST["DELIVERY_ID"])
		);
	$shipmentItemCollection = $shipment->getShipmentItemCollection();

	foreach ($basket as $basketItem)
		{
			$item = $shipmentItemCollection->createItem($basketItem);
			$item->setQuantity($basketItem->getQuantity());
		}
	$paymentCollection = $order->getPaymentCollection();
	$payment = $paymentCollection->createItem(
			Bitrix\Sale\PaySystem\Manager::getObjectById($_POST["PAY_SYSTEM_ID"])
		);
	$payment->setField("SUM", $order->getPrice());
	$payment->setField("CURRENCY", $order->getCurrency());
	$result = $order->save();

	echo "order_id:".$order->getId();
	CSaleBasket::DeleteAll($USER->GetID(), False);
}
elseif($_GET["form"] == 2)
{
	$name = $_POST["name"];
	$name = explode(" " , $name);
	$name = $name[0];
	$name = iconv("UTF-8" , "Windows-1251" , $name);
	
	$lname = $_POST["name"];
	$lname = explode(" " , $lname);
	$lname = $lname[1];
	$lname = iconv("UTF-8" , "Windows-1251" , $lname);
	
	$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP!№;%:?*()-=+";
	$p_max = 12;
	
	$password = null;
	while($p_max--)
		$password.=$chars[rand(0,strlen($chars)-1)]; 
	
	$user = new CUser;
	$arFields = Array(
	  "NAME"              => $name,
	  "LAST_NAME"         => $lname,
	  "PHONE"			  => $_POST["tel"],
	  "EMAIL"             => $_POST["email"],
	  "LOGIN"             => $_POST["email"],
	  "LID"               => "ru",
	  "ACTIVE"            => "Y",
	  "GROUP_ID"          => array(10,11),
	  "PASSWORD"          => $password,
	  "CONFIRM_PASSWORD"  => $password,
	  "PERSONAL_PHOTO"    => "",
	);

	$ID = $user->Add($arFields);
	if (intval($ID) > 0)
		echo $ID;
	else
		echo $user->LAST_ERROR;
}
elseif($_GET["form"] == 3)
{
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
	
	$oldBasket = $arBasketItems;
	unset($arBasketItems);
	
	$arBasketItems[0]["PRODUCT_ID"] = $_POST["id"];
	$arBasketItems[0]["NAME"] = iconv("UTF-8" , "Windows-1251" , $_POST["name"]);
	$arBasketItems[0]["PRICE"] = iconv("UTF-8" , "Windows-1251" , $_POST["price"]);
	$arBasketItems[0]["CURRENCY"] = iconv("UTF-8" , "Windows-1251" , "RUB");
	$arBasketItems[0]["QUANTITY"] = 1;
	
	$user_id = $_POST["user"];
	if($user_id == 0)
		$user_id = $USER->GetID();
	else
		$USER->Authorize($user_id);

	
	$basket = Bitrix\Sale\Basket::create("s1");

	foreach ($arBasketItems as $product)
		{
			$item = $basket->createItem("catalog", $product["PRODUCT_ID"]);
			unset($product["PRODUCT_ID"]);
			$item->setFields($product);
		}
	$order = Bitrix\Sale\Order::create("s1", $user_id);
	$order->setPersonTypeId(1);
	$order->setBasket($basket);
	$shipmentCollection = $order->getShipmentCollection();
	$shipment = $shipmentCollection->createItem(
			Bitrix\Sale\Delivery\Services\Manager::getObjectById(3)
		);
	$shipmentItemCollection = $shipment->getShipmentItemCollection();

	
	foreach ($basket as $basketItem)
		{
			$item = $shipmentItemCollection->createItem($basketItem);
			$item->setQuantity($basketItem->getQuantity());
		}
	$paymentCollection = $order->getPaymentCollection();
	$payment = $paymentCollection->createItem(
			Bitrix\Sale\PaySystem\Manager::getObjectById(5)
		);
	$payment->setField("SUM", $order->getPrice());
	$payment->setField("CURRENCY", $order->getCurrency());
	$result = $order->save();

	echo "order_id:".$order->getId();
	
	
	$basket = Bitrix\Sale\Basket::create("s1");
	foreach ($oldBasket as $product)
	{
		$item = $basket->createItem("catalog", $product["PRODUCT_ID"]);
		unset($product["PRODUCT_ID"]);
		$item->setFields($product);
	}
}
?>