<?
	session_start();
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	if(!$_POST["use"])
		die();
	
	switch($_POST["method"])
	{
		case 1:
			$_SESSION["order_step"] = $_POST["id"];
			echo $_SESSION["order_step"];
			break;
		case 2:
			$ml = mysqli_connect($DBHost, $DBLogin, $DBPassword, $DBName);
			$query = $ml->query("SET NAMES 'utf8'"); 
			$query = $ml->query("SET CHARACTER SET 'utf8'");
			$query = $ml->query("SET SESSION collation_connection = 'utf8_general_ci'");
			$query = $ml->query("select ID from b_user where EMAIL = '".$_POST["email"]."'");
			if(mysqli_num_rows($query) != 0)
				echo 1;
			else
				echo 0;
			break;
		case 3:
			$name = iconv("UTF-8" , "Windows-1251" , $_POST["name"]);
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$password = randString(7, array("abcdefghijklnmopqrstuvwxyz","ABCDEFGHIJKLNMOPQRSTUVWX­YZ","0123456789","!@#\$%^&*()",));

			$user = new CUser;
			$arFields = Array(
					 "NAME"              => $name,
					 "LAST_NAME"         => "",
					 "PHONE"			  => $phone,
					 "EMAIL"             => $email,
					 "LOGIN"             => $email,
					 "LID"               => "ru",
					 "ACTIVE"            => "Y",
					 "GROUP_ID"          => array(10,11),
					 "PASSWORD"          => $password,
					 "CONFIRM_PASSWORD"  => $password,
					 "PERSONAL_PHOTO"    => "",
				);

				$ID = $user->Add($arFields);
				if (intval($ID) > 0)
				{
					$to      = $email;
					$headers = 'Content-type: text/html; charset=Windows-1251' . "\r\n" .
						'From: webmaster@technoraum.ru' . "\r\n" .
						'Reply-To: webmaster@technoraum.ru' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
					$subject = "Информация о пользователе";
					$message = "Информационное сообщение сайта technoraum.ru<br>
						------------------------------------------<br>
						".$name.",<br>
						Ваша регистрационная информация:<br>
						ID пользователя: ".$ID."<br>
						Статус профиля: активен<br>
						Login: user".$i."<br><br>
						Password: ".$password."
						Вы можете изменить пароль в личном кабинете пользователя:
						<a href='http://technoraum.ru/personal/private/'>http://technoraum.ru/personal/private/</a><br><br>
						Сообщение сгенерировано автоматически.";
					mail($to, $subject, $message, $headers);
				}
				$i++;

			$_SESSION["order_step"] = $_POST["id"];
			unset($_SESSION["order_info"]);
			$_SESSION["order_info"]["user"] = array("email" => $email , "phone" => $phone, "name" => $name);
			echo $ID;
			$USER->Authorize($ID);
			break;
		case 4:
			$_SESSION["order_step"] = $_POST["id"];
			unset($_SESSION["order_info"]);
			$_SESSION["order_info"]["user"] = array("email" => $_POST["email"] , "phone" => $_POST["phone"], "name" => $_POST["name"]);
			break;
		case 5:
		require_once($_SERVER["DOCUMENT_ROOT"]."/personal/order/make/examples/calc_deliv_cdek_php/CalculatePriceDeliveryCdek.php");
			try 
			{
				$_POST["tariffList1"] = 137;
				$_POST["tariffList2"] = 11;
				$calc = new CalculatePriceDeliveryCdek();
				$calc->setSenderCityId($_POST['senderCityId']);
				$calc->setReceiverCityId($_POST['receiverCityId']);
				$calc->setDateExecute($_POST['dateExecute']);
				$calc->addTariffPriority($_POST['tariffList1']);
				$calc->addTariffPriority($_POST['tariffList2']);
				$calc->setModeDeliveryId($_POST['modeId']);
				$calc->addGoodsItemBySize($_POST['weight1'], $_POST['length1'], $_POST['width1'], $_POST['height1']);
				$calc->addGoodsItemByVolume($_POST['weight2'], $_POST['volume2']);

				if ($calc->calculate() === true) 
				{
					$res = $calc->getResult();
					echo "ok|/personal/order/make/img/cdek.jpg|СДЭК курьерская доставка|" . $res['result']['price'] . "|" . $res['result']['deliveryPeriodMin'] . "|" . $res['result']['deliveryPeriodMax'] . "|" . $res['result']['deliveryDateMin'] . "|" . $res['result']['deliveryDateMax'] . "|" .$res['result']['tariffId'];
				}
				else 
				{
					$err = $calc->getError();
					if( isset($err['error']) && !empty($err) ) 
					{
						echo "error|";
						foreach($err['error'] as $e) 
						{
							echo iconv("UTF-8" , "Windows-1251" , $e['code'] . "|" . $e['text']);
						}
					}
				}
				
				//раскомментируйте, чтобы просмотреть исходный ответ сервера
				// var_dump($calc->getResult());
				// var_dump($calc->getError());

			} catch (Exception $e) {
				echo 'Ошибка: ' . $e->getMessage() . "<br />";
			}
			break;
		case 6:
			CModule::IncludeModule("iblock");
			CModule::IncludeModule("sale");
			
			if($_POST["dil"])
				$DELIVERY = $_POST["dil"];
			else
				$DELIVERY = $_SESSION["DELIVERY"];			
			if($_POST["pay"])
				$PAYMENT = $_POST["pay"];
			else
				$PAYMENT = $_SESSION["PAYMENT"];
				
			$arBasketItems = $_SESSION["user_cart"];
			$user_id = $_SESSION["user_id"];
			
			$basket = Bitrix\Sale\Basket::create("s1");
			foreach ($arBasketItems as $product)
			{
				$item = $basket->createItem("catalog", $product["PRODUCT_ID"]);
				unset($product["PRODUCT_ID"]);
				$item->setFields($product);
			}
			
			if($_POST["client_name"] || $_POST["client_phone"] || $_POST["client_email"] || $_POST["client_dtext"])
			{
				if($_POST["client_name"])
					$comm = "Заберет другой человек: \nИмя: ".iconv("UTF-8" , "Windows-1251" , $_POST["client_name"]);
				if($_POST["client_phone"])
					$comm .= "\nТелефон: ".iconv("UTF-8" , "Windows-1251" , $_POST["client_phone"]);
				if($_POST["client_email"])
					$comm .= "\nE-mail: ".iconv("UTF-8" , "Windows-1251" , $_POST["client_email"]);		
				if($_POST["client_dtext"])
					$comm .= "\nКомментарий: ".iconv("UTF-8" , "Windows-1251" , $_POST["client_dtext"]);
			}
			else if($_POST["dtext"])
			{
				if($_POST["callback"])
					$comm = "Заказ подтвержден, перезванивать не нужно";
				else
					$comm = "Перезвонить: ".iconv("UTF-8" , "Windows-1251" , $_POST["time"])."\n";
				$comm .= "Комментарий: ".iconv("UTF-8" , "Windows-1251" , $_POST["dtext"])."\n";
			}
			
			$order = Bitrix\Sale\Order::create("s1", $user_id);
			$order->setPersonTypeId(1);
			$order->setBasket($basket);
			
			if($comm)
				$order->setField("USER_DESCRIPTION", $comm);
			
			
			
			$shipmentCollection = $order->getShipmentCollection();
			$shipment = $shipmentCollection->createItem(
				Bitrix\Sale\Delivery\Services\Manager::getObjectById($DELIVERY)
			);
			$shipmentItemCollection = $shipment->getShipmentItemCollection();
			
			foreach ($basket as $basketItem)
			{
				$item = $shipmentItemCollection->createItem($basketItem);
				$item->setQuantity($basketItem->getQuantity());
			}
			$paymentCollection = $order->getPaymentCollection();
			$payment = $paymentCollection->createItem(
					Bitrix\Sale\PaySystem\Manager::getObjectById($PAYMENT)
			);

			$all_summ = $order->getPrice();
			if(!empty($_POST["del_price"]))
				$all_summ += $_POST["del_price"]*1;

			$payment->setField("SUM", $all_summ);
			$payment->setField("CURRENCY", $order->getCurrency());
			
			
			
			$result = $order->save();

			$time = date('d')."-".date('m')."-".date('Y')." ".date('G').":".date('s');
			$to      = "zakaz@technoraum.ru";
			$subject = "Создан новый заказ на сайте ".$_SERVER["SERVER_NAME"];
			$message = "На сайте ".$_SERVER["SERVER_NAME"]." сформирован новый заказ:\nID пользователя: ".$user_id."\nСумма заказа: ".$order->getPrice()."\nДата и время оформления заказа: ".$time."\n\nСообщение сгенерировано автоматически.";
			
			$headers = 'From: webmaster@' . $_SERVER["SERVER_NAME"] . "\r\n" .
				'Reply-To: webmaster@example.com' . $_SERVER["SERVER_NAME"] . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

			mail($to, $subject, $message, $headers);
			
			unset($_SESSION["order_step"]);
			unset($_SESSION["user_cart"]);
			unset($_SESSION["user_id"]);
			unset($_SESSION["change_dmethod"]);
			unset($_SESSION["dil_address"]);
			unset($_SESSION["city_id"]);
			
			
			echo "order_id:".$order->getId();
		break;
	}
	session_write_close();
?>