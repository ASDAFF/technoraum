<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>


<div class="delivery-door">

	<div class="delivery-door-main">

		<div class="delivery-door-item">

			<div class="delivery-door-title">Где вы хотите получить заказ</div>
			<div class="delivery-door-input">
				<input type="text" placeholder="Населенный пункт" id="city" class="ui-autocomplete-input" value="<?=$_SESSION['IPOLSDEK_city'];?>" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
			</div>
			<div class="delivery-door-button">
				<input type="submit" value="Посчитать" id="delivery-door-send">
				<input type="submit" onclick="window.location.href='/catalog/element/?action=BUY&id=<?=$arParams[PRODUCT_ID]?>'" style="background: #feee35;color: #000000;" value="Добавить в корзину">
			</div>

		</div>

		<div class="delivery-door-item" style="text-align: left">
			<div class="delivery-door-title">Вес и габариты</div>
			<div class="delivery-door-param">Вес брутто: <span><?=$arParams['WEIGHT']?></span> грамм.</div>
			<div class="delivery-door-param">Габариты: <span><?=$arParams['WIDTH']?> x <?=$arParams['HEIGHT']?> x <?=$arParams['LENGTH']?></span> мм.</div>
		</div>

		<div class="clear"></div>

	</div>

	<div class="delivery-table" id="delivery-table">
		<div class="delivery-door-table-title">Доставка</div>

		<div class="delivery-door-table">

			<div class="row">
				<div class="col1 item"><div>транспортная компания</div></div>
				<div class="col2 item"><div>стоимость доставки</div></div>
				<div class="col3 item"><div>срок доставки</div></div>
			</div>

		</div>
	</div>

</div>

<script>
	$('#delivery-door-send').click(function(){
		$('.row.delivery').remove();
		var city = $('#city').val();
		if(city.length > 0){
			var data = {};
			data['GOODS'] = <?=$arResult['GOODS']?>;
			data['CITY'] = city;
			data['PATH_IMG'] = "<?=$templateFolder?>";

			$.ajax({
				type: "POST",
				url: "<?=$templateFolder.'/ajax.php'?>",
				data: data
			}).done(function( msg ) {
				$(".delivery-door-table").append(msg);
				$("#delivery-table").show();
			});
		}
		return false;
	});
</script>