<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<input type="hidden" name="del_method" value="1" />
<script src="/personal/order/make/js/step2_tab.js" type="text/javascript"></script>
<div class="left_block">
	<div class="info">
		<div class="l"><span>розничные магазины</span></div>
		<div class="r"><span class="time">когда можно забрать</span></div>
	</div>
	<div style="clear:both"></div>
	<ul class="st11_city">
	<?
		unset($order_shops_sections);
		unset($order_shops_items);
		CModule::IncludeModule("iblock");

		$ar_result = CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>"6"),false, Array()); 
		while($res = $ar_result->GetNext())
			$order_shops_sections[] = array("id" => $res["ID"] , "name" => $res["NAME"]);

		$center_x = 0;
		$center_y = 0;

		foreach($order_shops_sections as $section)
		{
			$temp = array();
			$ar_result = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>"6" , "IBLOCK_SECTION_ID" => $section["id"]),false, Array()); 
			while($res = $ar_result->GetNext())
			{
				$props = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>"6" , "ID" => $res["ID"]),false, Array(),Array("PROPERTY_PHONE" , "PROPERTY_TIME" , "PROPERTY_DAY" , "PROPERTY_MAP" , "PROPERTY_MAP_CORDS"));
				$props = $props->GetNext();

				$cords = file_get_contents("https://geocode-maps.yandex.ru/1.x/?apikey=f6c058d9-5ad4-4541-a90d-46aa6d41bf35&geocode=".$res["NAME"]);
				$cords = explode("pos>" , $cords);
				$cords = explode("</" , $cords[1]);
				$cords = explode(" " , $cords[0]);

				$tmp = array("CORD_X" => $cords[0] , "CORD_Y" => $cords[1] , "name" => $res["NAME"] , "phone" => $props["PROPERTY_PHONE_VALUE"] , "time" => $props["PROPERTY_TIME_VALUE"] , "day" => $props["PROPERTY_DAY_VALUE"] , "map" => $props["PROPERTY_MAP_VALUE"]);
				$temp[] = $tmp;
				$center_x += $cords[0];
				$center_y += $cords[1];
			}
			$order_shops_items[] = $temp;
		}

		$center_x = $center_x / count($order_shops_items);
		$center_y = $center_y / count($order_shops_items);

		$m = 0;
		$has_city = -1;
		foreach($order_shops_sections as $section)
		{
			unset($point_name);
			unset($point_phone);
			unset($point_time);
			unset($point_day);
			unset($point_map);
			unset($cord_x);
			unset($cord_y);
			?>
				<li <? if($_SESSION["city"] == $section["name"]) { $has_city = $m; echo "class='active'";}?>>
					<a href="#" class="city_title"><?=$section["name"]?></a>
					<ul>
						<?
							$k = 0;
							$index = 1;
							foreach($order_shops_items[$m] as $item)
							{
								$point_name 	.= $item["name"] 	. "&&&";
								for($i=0;$i<=count($item["phone"])-1;$i++)
									$point_phone 	.= $item["phone"][$i] 	. "|";
								$point_phone .= "&&&";
								for($i=0;$i<=count($item["time"])-1;$i++)
									$point_time 	.= $item["time"][$i] 	. "|";
								$point_phone .= "&&&";	
								$point_day	 	.= $item["day"] 	. "&&&";
								$point_map	 	.= $item["map"] 	. "&&&";
								$map = explode(" ", $item["map"]);
								$cord_x[$k] = $map[0];
								$cord_y[$k] = $map[1];
								$k++;
								?>
									<li style="display:flex">
										<div class="radio-btn btn1" data-id="">
											<div></div>
										</div>
										<div class="name" style="display:flex;padding-left:15px">
											<a data-id="<?=$index?>"><?=$item["name"]?></a>
										</div>
										<div class="call-manager">
											<a href="#callback2_popup" class="fancy">Уточнить у менеджера</a>
										</div>
									</li>
								<?
								$index++;
							}
						?>
					</ul>
					<input type="hidden" name="item_point_name" value="<?=$point_name?>" />
					<input type="hidden" name="item_point_phone" value="<?=$point_phone?>" />
					<input type="hidden" name="item_point_time" value="<?=$point_time?>" />
				</li>
			<?
			$m++;
		}
					if($has_city != -1)
					{
						unset($point_name);
						unset($point_phone);
						unset($point_time);
						unset($point_day);
						unset($point_map);
						unset($cord_x);
						unset($cord_y);
						
						foreach($order_shops_items[$has_city] as $item)
						{
							$point_name 	.= $item["name"] 	. "&&&";
							
							for($i=0;$i<=count($item["phone"])-1;$i++)
								$point_phone 	.= $item["phone"][$i] 	. "|";
							$point_phone .= "&&&";
							
							for($i=0;$i<=count($item["time"])-1;$i++)
								$point_time 	.= $item["time"][$i] 	. "|";
							$point_phone .= "&&&";	
							
							$point_day	 	.= $item["day"] 	. "&&&";
						}
						
						?>
							<input type="hidden" name="city" value="<?=$_SESSION["city"]?>" />
							<input type="hidden" name="temp1" />
							<input type="hidden" name="temp2" value="1"/>
						<?
					}
					else
					{
						unset($point_name);
						unset($point_phone);
						unset($point_time);
						unset($point_day);
						unset($point_map);
						unset($cord_x);
						unset($cord_y);
						
						for($i=0;$i<=count($order_shops_items)-1;$i++)
						{
							foreach($order_shops_items[$i] as $item)
							{
								$point_name 	.= $order_shops_sections[$i]["name"].", ".$item["name"] 	. "&&&";
								
								for($j=0;$j<=count($item["phone"])-1;$j++)
									$point_phone 	.= $item["phone"][$j] 	. "|";
								$point_phone .= "&&&";
								
								for($j=0;$j<=count($item["time"])-1;$j++)
									$point_time 	.= $item["time"][$j] 	. "|";
								$point_phone .= "&&&";	
								
								$point_day	 	.= $item["day"] 	. "&&&";
							}
						}
					}
				?>
				</ul>
			</div>
			<div class="right_block" id="map"></div>
			<div style="clear:both;"></div>
			
<script type="text/javascript" src="/bitrix/templates/TechnoRaum/css/maskedinput.js"></script>
<script>
$(document).ready(function()
{
	$("input[name='tel']").mask("+7(999)-999-99-99");
	$(".nstep input[name='change_dmethod']").val('1');
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
					var url = $("input[name='to-cart-action']").val();
					if(url)
						$.post(url);
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

			$(".st11_city li").each(function()
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
});
</script>