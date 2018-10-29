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
			$connect = mysql_connect($DBHost, $DBLogin, $DBPassword);
			$query = mysql_select_db($DBName);
			$query = mysql_query("SET NAMES 'utf8'"); 
			$query = mysql_query("SET CHARACTER SET 'utf8'");
			$query = mysql_query("SET SESSION collation_connection = 'utf8_general_ci'");
			$query = mysql_query("select ID from b_user where EMAIL = '".$_POST["email"]."'");
			if(mysql_num_rows($query) != 0)
				echo 1;
			else
				echo 0;
			break;
		case 3:
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$password = randString(7, array("abcdefghijklnmopqrstuvwxyz","ABCDEFGHIJKLNMOPQRSTUVWX­YZ","0123456789","!@#\$%^&*()",));
			
			$i = 1;
			while(true)
			{
				if($i == 50)
				{
					echo "error";
					die();
				}
				$user = new CUser;
				$arFields = Array(
					 "NAME"              => $name,
					 "LAST_NAME"         => "",
					 "PHONE"			  => $phone,
					 "EMAIL"             => $email,
					 "LOGIN"             => "user".$i,
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
					break;
				}
				$i++;
			}
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
			include_once("CalculatePriceDeliveryCdek.php");
			try 
			{

				//создаём экземпляр объекта CalculatePriceDeliveryCdek
				$calc = new CalculatePriceDeliveryCdek();
				
				//Авторизация. Для получения логина/пароля (в т.ч. тестового) обратитесь к разработчикам СДЭК -->
				//$calc->setAuth('authLoginString', 'passwordString');
				
				//устанавливаем город-отправитель
				$calc->setSenderCityId($_POST['senderCityId']);
				//устанавливаем город-получатель
				$calc->setReceiverCityId($_POST['receiverCityId']);
				//устанавливаем дату планируемой отправки
				$calc->setDateExecute($_POST['dateExecute']);
				
				//устанавливаем тариф по-умолчанию
				$calc->setTariffId('11');
					
				//устанавливаем режим доставки
				$calc->setModeDeliveryId($_POST['modeId']);
				//добавляем места в отправление
				//$calc->addGoodsItemBySize($_POST['weight1'], $_POST['length1'], $_POST['width1'], $_POST['height1']);
				$calc->addGoodsItemByVolume($_POST["weight"], $_POST["volume"]);
				
				if ($calc->calculate() === true) 
				{
					$res = $calc->getResult();
					
					echo "ok|img/cdek.jpg|СДЭК курьерская доставка|" . $res['result']['price'] . "|" . $res['result']['deliveryPeriodMin'] . "|" . $res['result']['deliveryPeriodMax'] . "|" . $res['result']['deliveryDateMin'] . "|" . $res['result']['deliveryDateMax'] . "|" .$res['result']['tariffId'];
					
					/*
					if(array_key_exists('cashOnDelivery', $res['result'])) 
					{
						echo 'Ограничение оплаты наличными, от (руб): ' . $res['result']['cashOnDelivery'] . '.<br />';
					}
					*/
				}
				else 
				{
					$err = $calc->getError();
					if( isset($err['error']) && !empty($err) ) 
					{
						echo "error|";
						foreach($err['error'] as $e) 
						{
							echo $e['code'] . "|" . $e['text'];
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
			
			
			$arBasketItems = $_SESSION["user_cart"];
			$user_id = $_SESSION["user_id"];
			
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
				Bitrix\Sale\Delivery\Services\Manager::getObjectById($_SESSION["DELIVERY"])
			);
			$shipmentItemCollection = $shipment->getShipmentItemCollection();
			
			foreach ($basket as $basketItem)
			{
				$item = $shipmentItemCollection->createItem($basketItem);
				$item->setQuantity($basketItem->getQuantity());
			}
			$paymentCollection = $order->getPaymentCollection();
			$payment = $paymentCollection->createItem(
					Bitrix\Sale\PaySystem\Manager::getObjectById($_SESSION["PAYMENT"])
			);
			$payment->setField("SUM", $order->getPrice());
			$payment->setField("CURRENCY", $order->getCurrency());
			$result = $order->save();

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