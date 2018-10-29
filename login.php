<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

function isUserPassword($userId, $password)
{
    $userData = CUser::GetByID($userId)->Fetch();

    $salt = substr($userData['PASSWORD'], 0, (strlen($userData['PASSWORD']) - 32));

    $realPassword = substr($userData['PASSWORD'], -32);
    $password = md5($salt.$password);

    return ($password == $realPassword);
}


$filter = Array("EMAIL" => $_POST["login"]);
$sql = CUser::GetList(($by="id"), ($order="desc"), $filter);
if($sql->NavNext(true, "f_"))
	   $id_user = $f_ID;
if($id_user)
{
	$result = isUserPassword($id_user, $_POST["password"]);
	if($result == 1)
	{
		echo 1;
		$USER->Authorize($id_user);
	}
	else
		echo 0;
}
else
	echo 0;
?>