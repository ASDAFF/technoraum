<?
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	if($_POST["change_dmethod"])
		$_SESSION["change_dmethod"] = $_POST["change_dmethod"];
	if($_POST["dil_address"])
		$_SESSION["dil_address"] = $_POST["dil_address"];
	if($_POST["city_id"])
		$_SESSION["city_id"] = $_POST["city_id"];	

	if($_POST["del_price"])
		$_SESSION["del_price"] = $_POST["del_price"];

	$method = $_SESSION["change_dmethod"];
	if(!$method)
		$method = 1;
	
	$addr = $_SESSION["dil_address"];
	$city_id = $_SESSION["city_id"];

?>
<p>Выберите способ оплаты</p>
<form id="payment_method" action="/personal/order/make/" method="POST" style="position:relative;">
	<input type="hidden" name="city_id" value="<?=$city_id?>" />
	<input type="hidden" name="del_price" value="<?=$_POST["del_price"]?>" />
	<input type="hidden" name="dmethod" value="<?=$method?>" />
	<input type="hidden" name="daddr" value="<?=$addr?>" />
	<input type="hidden" name="change_step" value="4" />
	<div class="row">
		<div class="radio-btn btn1 <? if($_SESSION["paym"] == 1) echo "check"; ?>" data-id="1">
			<div></div>
		</div>
		<div class="name" style="display:inline-block;padding-left:15px">
			<span>Оплата наличными или картой при получении</span>
		</div>
	</div>
	<div class="row">
		<div class="radio-btn btn1 <? if($_SESSION["paym"] == 2) echo "check"; ?>" data-id="2">
			<div></div>
		</div>
		<div class="name" style="display:inline-block;padding-left:15px">
			<span>Онлайн оплата</span>
		</div>
		<div class="pay_methods">
			<img src="/personal/order/make/img/pay4.png" />
			<img src="/personal/order/make/img/pay5.png" />
			<img src="/personal/order/make/img/pay6.png" />
		</div>
	</div>
	<div class="row">
		<div class="radio-btn btn1 <? if($_SESSION["paym"] == 3) echo "check"; ?>" data-id="3">
			<div></div>
		</div>
		<div class="name" style="display:inline-block;padding-left:15px">
			<span>Банковской картой</span>
		</div>
		<div class="pay_methods">
			<img src="/personal/order/make/img/pay1.png" />
			<img src="/personal/order/make/img/pay2.png" />
			<img src="/personal/order/make/img/pay3.png" />
			<img src="/personal/order/make/img/pay7.png" />
		</div>
	</div>

	<input type="submit" value="Перейти к подтверждению заказа" />
	<input required type="text" name="paym" value="<?=$_SESSION["paym"]?>" style="position:absolute;top:0;left:0;opacity:0;z-index:-1"/>
	<a href="/personal/order/make/?step=2" class="back-click">Вернутся к выбору способа получения</a>
</form>
<style>
	.back-click{font-weight:bold;color:#2067b0;position:relative;cursor:pointer;font-size:14px;padding-left:50px}
	.back-click:after{content: " ";display: block;position: absolute;top: 8px;left: 14px;margin-left: 10px;margin-top: -3px;width: 14px;height: 8px;background: url(/bitrix/templates/TechnoRaum/img/arrow_down.png) no-repeat center center;transform: rotate(90deg);background-size: 100% 100%;}
</style>
<script>
$(document).ready(function()
{
	$(".radio-btn").click(function()
	{
		var iclass = $(this).attr("class");
		var text = $(this).next(".name").text();
		text = text.replace(" " , "");
		text = text.replace("." , ". ");
		text = text.replace("," , ", ");
		
		iclass = iclass.replace("radio-btn " , "");
		iclass = iclass.replace("check" , "");
		iclass = iclass.replace(" ", "");
		
		$(".radio-btn."+iclass).removeClass("check");
		$(this).addClass("check");
		$("input[name='paym']").val($(this).attr("data-id"));
	});
	$(".row .name span").click(function()
	{
		$(this).parents(".row").find(".radio-btn").trigger("click");
	});

});
</script>
	