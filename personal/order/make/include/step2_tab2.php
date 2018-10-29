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
			
			if(!$_SESSION["ALTASIB_GEOBASE_CODE"]["CITY"]["NAME"])
				$_SESSION["ALTASIB_GEOBASE_CODE"]["CITY"]["NAME"] = "Краснодар";
			//Если в городе клиента есть точки самовывоза
			if(in_array($_SESSION["city"] , $city))
			{
				$i = 0;
				foreach($stocks as $stock)
				{
					if($stock["city"] == $_SESSION["ALTASIB_GEOBASE_CODE"]["CITY"]["NAME"])
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
	$(".name a").click(function()
	{
		$(this).closest("li").find(".radio-btn").trigger("click");
	});
	$(".radio-btn").click(function()
	{
		var city = $("input[name='city']").val();
		var adr = $(this).parent().find(".name").children("a").text();
		adr = adr.replace(" ", "");
		adr = adr.replace(",", "");
		adr = adr.replace("&nbsp;","");
		adr = adr.replace(".","");
		adr = adr.replace("\r\n" , "");
		adr = adr.replace("\r" , "");
		adr = adr.replace("\n" , "");
		adr = adr.replace("улица","");
		adr = adr.replace("ул","");
		adr = adr.split(" ");
		adr = adr[0];

		var success = 0;
		for(index=0;index<=MAP_ITEMS.length-1;index++)
		{
			var iname = MAP_ITEMS[index]["NAME"];
			iname = iname.replace(" ", "");
			iname = iname.replace(",", "");
			iname = iname.replace("&nbsp;","");
			iname = iname.replace(".","");
			iname = iname.replace("улица","");
			iname = iname.replace("ул","");
			iname = iname.split(" ");
			iname = iname[0];

			console.log(iname+" --- "+adr);
			if(iname == adr)
			{
				success = 1;
				break;
			}
		}

		if(success == 1)
		{
			var placemark = new ymaps.Placemark([MAP_ITEMS[index]["X"], MAP_ITEMS[index]["Y"]], {balloonContentHeader: MAP_ITEMS[index]["CONTENT1"] , balloonContentBody: MAP_ITEMS[index]["CONTENT2"] , balloonContentFooter : MAP_ITEMS[index]["CONTENT3"]} , {preset: 'islands#icon',iconColor: '#FF0000'});
			myMap.geoObjects.add(placemark);
			placemark.balloon.open();
			setTimeout(function()
		    {
				$(".pp_form").submit(function(e)
				{
					$(".pp_form").submit(function(e)
					{
						var url = $("input[name='to-cart-action']").val();
						if(url)
							$.post(url);
					});
				});
		    },1000);
		}
	});

	var Win_interval = setInterval(function()
	{
		var obj = $("#map .pp_form");
		if(obj.length)
		{
			var title = obj.parent().parent().parent().find(".pp_block1").children("p").filter(":last-child").text();
			title = title.replace("," , "");
			title = title.replace("." , "");
			title = title.replace(" " , "");
			title = title.replace(" ", "");
			title = title.replace(",", "");
			title = title.replace("&nbsp;","");
			title = title.replace(".","");
			title = title.replace("улица","");
			title = title.replace("ул","");
			title = title.split(" ");
			title = title[0].toUpperCase();
			$(".left_block ul li").each(function()
			{
				var ititle = $(this).find(".name").children("a").text();
				ititle = ititle.replace("," , "");
				ititle = ititle.replace("." , "");
				ititle = ititle.replace(" " , "");
				ititle = ititle.replace(" ", "");
				ititle = ititle.replace(",", "");
				ititle = ititle.replace("&nbsp;","");
				ititle = ititle.replace(".","");
				ititle = ititle.replace("улица","");
				ititle = ititle.replace("ул","");
				ititle = ititle.split(" ");
				ititle = ititle[0].toUpperCase();

				console.log(title + " ------ " + ititle);
				if(title == ititle)
				{
					$(this).find(".name").children("a").trigger("click");
					setTimeout(function()
					{
						$(".pp_form").submit(function(e)
						{
							var url = $("input[name='to-cart-action']").val();
							if(url)
								$.post(url);
						});
					},1000);
				}
			});
		}
	},1000);
	
	var interval = setInterval(function()
	{
		var temp2 = $("input[name='temp2']").val();
		if(temp2)
		{
			clearInterval(interval);
			$(".nstep input[name='city_id']").val(temp2);
		}
	},500);
	
});
</script>
