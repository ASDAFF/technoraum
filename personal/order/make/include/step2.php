<?
	$tabs = array("Самовывоз из магазина" , "Самовывоз из пункта выдачи", "Доставка до двери");
	$_SESSION["city"] = $_SESSION["ALTASIB_GEOBASE_CODE"]["CITY"]["NAME"];
?>
<p class="main_title">Выберите способ получения заказа</p>
<form class="step_form" id="step2_form">
	<div class="tabs step2">
	<?
		for($i=1;$i<=count($tabs);$i++)
		{
			?>
				<div data-id="<?=$i?>" class="tab<?=$i?> tab <? if($i == 1) echo 'active'; ?>"><a tab-id="<?=$i?>"><?=$tabs[$i-1]?></a></div>
			<?
		}
	?>
	</div>
	<div class="tabs_content">
		<div class="tbc tbc1"></div>
		<div class="tbc tbc2"></div>
		<div class="tbc tbc3"></div>
	</div>
</form>
			<form style="position:relative" method="POST" action="/personal/order/make/" class="nstep">
				<input type="hidden" name="point_name" value="<?=$point_name?>" />
				<input type="hidden" name="point_phone" value="<?=$point_phone?>" />
				<input type="hidden" name="point_time" value="<?=$point_time?>" />
				<input type="hidden" name="city" value="" />
				<input type="hidden" name="temp1" />
				<input type="hidden" name="temp2" value="1"/>
				<input type="hidden" name="city_id" value="1"/>
				<input type="hidden" name="change_dmethod" value="1">
				<input type="text" name="dil_address" required style="opacity:0;position:absolute;top:-300px;left:0;z-index:-1">
				<input type="hidden" name="change_step" value="3">
				<input type="submit" value="Перейти к способу оплаты" />
			</form>