<?
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	
	if($_POST["change_dmethod"])
		$_SESSION["change_dmethod"] = $_POST["change_dmethod"];
	if($_POST["dil_address"])
		$_SESSION["dil_address"] = $_POST["dil_address"];
	if($_POST["city_id"])
		$_SESSION["city_id"] = $_POST["city_id"];	
	
	$method = $_SESSION["change_dmethod"];
	$addr = $_SESSION["dil_address"];
	$city_id = $_SESSION["city_id"];
?>
<p>Выберите способ оплаты</p>
<form id="payment_method" action="/personal/order/make/" method="POST">
	<input type="hidden" name="city_id" value="<?=$city_id?>" />
	<input type="hidden" name="dmethod" value="<?=$method?>" />
	<input type="hidden" name="daddr" value="<?=$addr?>" />
	<input type="hidden" name="change_step" value="4" />
	<div class="row">
		<div class="radio-btn btn1 <? if($_SESSION["paym"] == 1) echo "check"; ?>" data-id="1">
			<div></div>
		</div>
		<div class="name" style="display:flex;padding-left:15px">
			<span>Оплата наличными или картой при получении</span>
		</div>
	</div>
	<div class="row">
		<div class="radio-btn btn1 <? if($_SESSION["paym"] == 2) echo "check"; ?>" data-id="2">
			<div></div>
		</div>
		<div class="name" style="display:flex;padding-left:15px">
			<span>Онлайн оплата</span>
		</div>
	</div>
	<div class="pay_methods">
		<img src="/personal/order/make/img/pay1.png" />
		<img src="/personal/order/make/img/pay2.png" />
		<img src="/personal/order/make/img/pay3.png" />
		<img src="/personal/order/make/img/pay4.png" />
		<img src="/personal/order/make/img/pay5.png" />
		<img src="/personal/order/make/img/pay6.png" />
	</div>
	<input type="submit" value="Перейти к способу оплаты" />
	<input required type="hidden" name="paym" />
</form>
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
});
</script>
	