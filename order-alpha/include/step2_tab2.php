<?
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	
	$file = "../data/stocks.csv";
	if(file_exists($file))
	{
		$open = fopen($file, "r+");
		if($open)
		{
			$i = 0;
			while($line = fgets($open))
			{
				if($i == 0)
				{
					$i++;
					continue;
				}
				$line = explode("&" , $line);
				
				//Наполняем массив городов
				$city[$i-1] = $line[3];
				$stocks[$i-1] = array
				(
					"region" => $line[2],
					"city" => $line[3],
					"address" => $line[5]." ".$line[6]." ".$line[7],
					"phone" => $line[11],
					"time" => $line[12]
				);
				$i++;
			}
			
			//Если в городе клиента есть точки самовывоза
			if(in_array($_SESSION["city"] , $city))
			{
				$i = 0;
				foreach($stocks as $stock)
				{
					if($stock["city"] == $_SESSION["city"])
					{
						$client_stocks[$i] = $stock;
						$i++;
					}
				}
				$stocks = $client_stocks;
				
				//Очищаем временные переменные
				unset($client_stocks);
				unset($point_name);
				unset($point_phone);
				unset($point_time);
				unset($point_day);
				unset($point_map);
				unset($cord_x);
				unset($cord_y);
				
				foreach($stocks as $item)
				{
					$point_name .= $item["address"]."&&&";
					$item["phone"] = explode(", ", $item["phone"]);
					for($i=0;$i<=count($item["phone"])-1;$i++)
						$point_phone 	.= $item["phone"][$i] 	. "|";

					$item["time"] = explode(", ", $item["time"]);
					for($i=0;$i<=count($item["time"])-1;$i++)
						$point_time 	.= $item["time"][$i] 	. "|";
					$point_phone .= "&&&";		
					$point_day	 	.= $item["day"] 	. "&&&";
					$point_map	 	.= $item["map"] 	. "&&&";
					$map = explode(" ", $item["map"]);
				}
				?>
					<input type="hidden" name="point_name" value="<?=$point_name?>" />
					<input type="hidden" name="point_phone" value="<?=$point_phone?>" />
					<input type="hidden" name="point_time" value="<?=$point_time?>" />
					<input type="hidden" name="city" value="<?=$_SESSION["city"]?>" />
					<input type="hidden" name="temp1" />
					<input type="hidden" name="temp2"/>
				<?
			}
			//Если их нет
			else
			{
				
				?><p>К сожалению в вашем городе нет точек самовывоза СДЭК. Пожалуйста, выберете ближайший пункт выдачи:</p><?
				
			}
			?>
				<input type="hidden" name="del_method" value="2" />
				<script src="/personal/order/make/js/step2_tab.js" type="text/javascript"></script>
				<div class="left_block">
					<div class="info">
						<div class="l full"><span>Пункты выдачи сдэк в вашем городе</span></div>
					</div>
					<ul>
						<?
							foreach($stocks as $item)
							{
								?>
									<li style="display:flex">
										<div class="radio-btn btn1" data-id="">
											<div></div>
										</div>
										<div class="name" style="display:flex;padding-left:15px">
											<a><?=$item["address"]?></a>
										</div>
								</li><?
							}
						?>
					</ul>
				</div>
				<div class="right_block" id="map"></div>
				<div style="clear:both;"></div>
			<?
		}
		else
			echo "<p class='error'>Ошибка. Невозможно открыть список городов</p>";
	}
	else
	{
		echo "<p class='error'>Ошибка. Список городов не найден</p>";
	}
?>
<script>
$(document).ready(function()
{
	$(".nstep input[name='change_dmethod']").val('2');
	$(".radio-btn").click(function()
	{
		var city = $(".nstep input[name='city']").val();
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
		$(".nstep input[name='dil_address']").val(city + ", " + text);
	});
});
</script>
