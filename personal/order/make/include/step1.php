<p class="main_title">Введите контактные данные</p>
<form class="step_form" <? if($USER->IsAuthorized()) echo 'id="step1_form_user"'; else echo 'id="step1_form"'; ?> action="<?=$_SERVER["REQUEST_URI"]?>" method="POST">
	<div class="form_block">
		<div class="input">
			<input placeholder="Электронная почта" type="email" <? if($USER->GetEmail()) echo "readonly value='".$USER->GetEmail()."'" ?> name="email" <? if(!$USER->IsAuthorized()) echo "required"; ?> />
		</div>
	</div>	
	<div class="form_block">
		<div class="input">
			<input placeholder="Телефон" type="text" <? if($_SESSION["order_info"]["user"]["phone"]) echo "value='".$_SESSION["order_info"]["user"]["phone"]."'" ?> name="tel" placeholder="+7(___) ___ __ __" <? if(!$USER->IsAuthorized()) echo "required"; ?> />
		</div>
	</div>	
	<div class="form_block">
		<div class="input">
			<input placeholder="Имя" type="text" <? if($_SESSION["order_info"]["user"]["name"]) echo "value='".iconv("UTF-8" , "Windows-1251" , $_SESSION["order_info"]["user"]["name"])."'" ?> name="name" <? if(!$USER->IsAuthorized()) echo "required"; ?> />
		</div>
	</div>
	<? 
		if(!$USER->IsAuthorized())
		{
			?>
			<div class="form_block agree_block">
				<input required type="checkbox" name="check" checked="checked"><span>Я согласен на <a href="/soglasie-na-obrabotku-personalnykh-dannykh/" target=_blank>обработку моих персональных данных</a></span>
			</div>
			<?
		}
	?>
	<div class="form_block">
		<input type="submit" value="Перейти к способу получения заказа" />
	</div>
	<div class="error"></div>
</form>
<script type="text/javascript" src="/bitrix/templates/TechnoRaum/css/maskedinput.js"></script>
<script>
$(document).ready(function()
{
	$("input[name='tel']").mask("+7(999)-999-99-99");
	$("#step1_form").submit(function(e)
	{
		e.preventDefault();
		var obj = $(this);
		var reg = false;
		$.post
		(
			"/personal/order/make/system.php",
			{
				use : 1,
				email : $(this).find("input[name='email']").val(),
				method : 2
			},
			function(data)
			{
				if(data == 1)
					$(".step_form .error").text("Пользователь с таким e-mail адресом уже существует. Войдите под своей учетной записью или используйте другой e-mail адрес");
				else
				{
					$.post
					(
						"/personal/order/make/system.php",
						{
							use : 1,
							id : 2,
							email : obj.find("input[name='email']").val(),
							phone : obj.find("input[name='tel']").val(),
							name  :  obj.find("input[name='name']").val(),
							method : 3
						},
						function(data)
						{
							if(data)
							{
								window.location.href=obj.attr("action");
							}
						}
					);
				}
			}
		);
	});
			
	$("#step1_form_user").submit(function(e)
	{
		var obj = $(this);
		$.post
		(
			"/personal/order/make/system.php",
			{
				use : 1,
				id : 2,
				email : obj.find("input[name='email']").val(),
				phone : obj.find("input[name='tel']").val(),
				name  :  obj.find("input[name='name']").val(),
				method : 4
			},
			function(data)
			{
				window.location.href=obj.attr("action");
			}
		);
	});	
});
</script>