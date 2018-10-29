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