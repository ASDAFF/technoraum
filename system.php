<?
	session_start();
	if($_POST["action"] == "add")
	{
		$ss = $_SESSION["comp"];
		if($ss)
			$_SESSION["comp"] = $ss."&".$_POST["id"];
		else
			$_SESSION["comp"] = $_POST["id"];
	}
	elseif($_POST["action"] == "del")
	{
		$ss = explode("&" , $_SESSION["comp"]);
		for($i=0;$i<=count($ss)-1;$i++)
		{
			if($ss[$i] != $_POST["id"])
				$temp .= "&".$ss[$i];
		}
		$_SESSION["comp"] = $temp;
	}
?>