<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$captcha = $_POST["g-recaptcha-response"];
$url = "https://www.google.com/recaptcha/api/siteverify";

$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL,$url); // ������������� URL �� ������� �������� ������  
curl_setopt($ch, CURLOPT_HEADER, 0); //  ��������� ����� ��������� ���������
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // ��������� ����� ��������� � ����������, � �� �������.
curl_setopt($ch, CURLOPT_TIMEOUT, 3); // ������� ����� 4 ������ 
curl_setopt($ch, CURLOPT_POST, 1); // ������������� ����� POST
curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=6Lf8KEkUAAAAACYivzqfdzpaAw5d5XnZedv72XSm&response=".$captcha); // ���������� ��������
$result = curl_exec($ch);  
curl_close($ch);

$result = explode("," , $result);
$result = $result[0];
$result = explode(":" , $result);
$result = $result[1];
$result = str_replace(" " , "" , $result);

if(substr($result , 0 , 4) == "true")
{
	$name = $_POST["name"];
	$name = explode(" " , $name);
	$name = $name[0];
	$name = iconv("UTF-8" , "Windows-1251" , $name);
		
	$lname = $_POST["lname"];
	$lname = explode(" " , $lname);
	$lname = $lname[1];
	$lname = iconv("UTF-8" , "Windows-1251" , $lname);
		
	$filter = Array("EMAIL" => $email);
	$sql = CUser::GetList(($by="id"), ($order="desc"), $filter);
	if($sql->NavNext(true, "f_"))
	   $id_user = $f_ID;
	   
	if($id_user)
		echo "������ e-mail ����� ��� ���������������";
	else
	{
		$i = 1;
			$user = new CUser;
			$arFields = Array(
				 "NAME"              => $name,
				 "LAST_NAME"         => "",
				 "PHONE"			  => $_POST["tel"],
				 "EMAIL"             => $_POST["email"],
				 "LOGIN"             => $_POST["email"],
				 "LID"               => "ru",
				 "ACTIVE"            => "Y",
				 "GROUP_ID"          => array(10,11),
				 "PASSWORD"          => $_POST["password"],
				 "CONFIRM_PASSWORD"  => $_POST["password"],
				 "PERSONAL_PHOTO"    => "",
			);

			$ID = $user->Add($arFields);
			if (intval($ID) > 0)
			{
				$to      = $_POST["email"];
				$headers = 'Content-type: text/html; charset=Windows-1251' . "\r\n" .
					'From: webmaster@technoraum.ru' . "\r\n" .
					'Reply-To: webmaster@technoraum.ru' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				$subject = "���������� � ������������";
				$message = "�������������� ��������� ����� technoraum.ru<br>
					------------------------------------------<br>
					".$name.",<br>
					���� ��������������� ����������:<br>
					ID ������������: ".$ID."<br>
					������ �������: �������<br>
					Login: user".$i."<br><br>
					�� ������ �������� ������ � ������ �������� ������������:
					<a href='http://technoraum.ru/personal/private/'>http://technoraum.ru/personal/private/</a><br><br>
					��������� ������������� �������������.";
				mail($to, $subject, $message, $headers);
			}

		echo $ID;
		$USER->Authorize($ID);
	}
	
}
else
{
	echo "���� ReCAPTCHA �� ���������"; 
}
?>