var map_in_use = 0;
var myMap;
var MAP_ITEMS = [];

function getCityID(city)
{
	$.ajax
	({
		url: "https://api.cdek.ru/city/getListByTerm/jsonp.php?callback=?",
		dataType: "jsonp",
		data: 
		{
			q : city
		},
		success: function(data) 
		{
			$.map(data.geonames, function(item)
			{
				if(!$("input[name='temp2']").val())
					$("input[name='temp2']").val(item.id);
				return;
			});
		}
	});
}
function CreateMap(dil) 
{
	if(map_in_use != 0)
		return;
	map_in_use = 1;
	$("#map").html("");
	$("input[name='temp1']").val('');
	var city = $("input[name='city']").val();
	var point_name = $("input[name='point_name']").val();
	if(!point_name)
	{
		point_name = $("input[name='item_point_name']").val();
		if(!point_name)
			return;
	}
	getCityID(city);
	var main_interval = setInterval(function()
	{
		var city_id = $("input[name='temp2']").val();
		
		if(city_id)
		{
			clearInterval(main_interval);
		
			if(point_name)
			{
				point_name = point_name.split("&&&");
				for(i=0,first=0;i<=point_name.length-1;i++,first++)
				{
					if(point_name[i])
					{
						var address = city + " , " + point_name[i];
						ymaps.geocode(address, {results : 1}).then(function (res)
						{
							// Выбираем первый результат геокодирования
							var firstGeoObject = res.geoObjects.get(0);
							var cords = firstGeoObject.geometry.getCoordinates();
								
							var temp = '';
							if(cords)
							{
								var map_y = cords[0];
								var map_x = cords[1];
								$("input[name='temp1']").val($("input[name='temp1']").val() + "|" + map_y + "*" + map_x + "*" + firstGeoObject.properties.get('name') + "|");
							}
						});
					}
				}
				var interval = setInterval(function()
				{
					var temp = $("input[name='temp1']").val();
					
					if(temp)
					{
						clearInterval(interval);
						map_in_use = 0;
						var cords = [];

						temp = temp.split("||");

						for(i=0,j=0;i<=temp.length-1;i++)
						{
							temp[i] = temp[i].replace("|" , "");
							if(temp[i])
							{
								cords[j] = temp[i];
								j++;
							}
						}
						
						var center_x = 0;
						var center_y = 0;
						
						if(cords)
						{
							if(cords.length > 1)
							{
								for(i=0;i<=cords.length-1;i++)
								{
									var line = cords[i];
									line = line.replace("|" , "");
									line = line.split("*");
									
									center_y += line[0] * 1;
									center_x += line[1] * 1;
								}
								center_y = center_y / cords.length;
								center_x = center_x / cords.length;
							}
							else
							{
								var line = cords[0];
								line = line.replace("|" , "");
								line = line.split("*");
								
								center_y = line[0];
								center_x = line[1];
							}
						}
						
						city = $("input[name='city']").val();
						nz = 12;
						if(!city)
							nz = 8;
						myMap = new ymaps.Map('map', 
						{
							center: [center_y, center_x],
							zoom: nz
						}, 
						{
							searchControlProvider: 'yandex#search'
						}),
						objectManager = new ymaps.ObjectManager
						({
							// Чтобы метки начали кластеризоваться, выставляем опцию.
							clusterize: true,
							// ObjectManager принимает те же опции, что и кластеризатор.
							gridSize: 32,
							clusterDisableClickZoom: true
						});
						
						// Чтобы задать опции одиночным объектам и кластерам,
						// обратимся к дочерним коллекциям ObjectManager.
						objectManager.objects.options.set('preset', 'islands#greenDotIcon');
						objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
						myMap.geoObjects.add(objectManager);
						
						var data = '{"type":"FeatureCollection","features":[';
						var city = $("input[name='city']").val();
						
						if(point_name)
						{
							if(point_name.length > 2)
							{
								for(i=0,first=0,index=0;i<=point_name.length-1;i++,first++)
								{
									var map = cords[i];
									if(map)
									{
										map = map.split("*");

										MAP_ITEMS[index] = [];

										MAP_ITEMS[index]["X"] = map[0];
										MAP_ITEMS[index]["Y"] = map[1];
										MAP_ITEMS[index]["NAME"] = map[2];
										MAP_ITEMS[index]["CONTENT1"] = "<div class='pp_block1'><p class='tt'>Адрес</p><p>"+map[2]+"</p></div>";
										MAP_ITEMS[index]["CONTENT2"] = "<div class='pp_block2'><p class='tt'>Телефон</p><p>8-918-325-70-02</p><p class='tt'>Режим работы</p><p>Пн–пт, 9:00–19:00<br>Сб–Вс, 10:00–17:00</p>";
										MAP_ITEMS[index]["CONTENT3"] = "<form class='pp_form' method='POST' action='/personal/order/make/'><input type='hidden' name='city_id' value='"+city_id+"' /><input type='hidden' name='change_dmethod' value='"+dil+"' /><input type='hidden' name='dil_address' value='"+$("input[name='city']").val() + ", " + map[2] + "'/><input type='hidden' name='change_step' value='3' /><input type='submit' value='Заберу здесь'></form>";
										
										var placemark = new ymaps.Placemark([MAP_ITEMS[index]["X"], MAP_ITEMS[index]["Y"]], {balloonContentHeader: MAP_ITEMS[index]["CONTENT1"] , balloonContentBody: MAP_ITEMS[index]["CONTENT2"] , balloonContentFooter : MAP_ITEMS[index]["CONTENT3"]} , {preset: 'islands#icon',iconColor: '#FF0000'});
										myMap.geoObjects.add(placemark);
										index++;
									}
									
								}
							}
							else
							{
								var map = cords[0];
								if(map)	
								{
									map = map.replace("|" , "");
									map = map.split("*");
									MAP_ITEMS[0] = [];
										
									MAP_ITEMS[0]["X"] = map[0];
									MAP_ITEMS[0]["Y"] = map[1];
									MAP_ITEMS[0]["NAME"] = point_name[0];
									MAP_ITEMS[0]["CONTENT1"] = "<div class='pp_block1'><p class='tt'>Адрес</p><p>"+point_name[0]+"</p></div>";
									MAP_ITEMS[0]["CONTENT2"] = "<div class='pp_block2'><p class='tt'>Телефон</p><p>8-918-325-70-02</p><p class='tt'>Режим работы</p><p>Пн–пт, 9:00–19:00<br>Сб–Вс, 10:00–17:00</p>";
									MAP_ITEMS[0]["CONTENT3"] = "<form class='pp_form' method='POST' action='/personal/order/make/'><input type='hidden' name='city_id' value='"+city_id+"' /><input type='hidden' name='change_dmethod' value='"+dil+"' /><input type='hidden' name='dil_address' value='"+$("input[name='city']").val() + ", " + point_name[0] + "'/><input type='hidden' name='change_step' value='3' /><input type='submit' value='Заберу здесь'></form>";
										
									var placemark = new ymaps.Placemark([MAP_ITEMS[0]["X"], MAP_ITEMS[0]["Y"]], {balloonContentHeader: MAP_ITEMS[0]["CONTENT1"] , balloonContentBody: MAP_ITEMS[0]["CONTENT2"] , balloonContentFooter : MAP_ITEMS[0]["CONTENT3"]} , {preset: 'islands#icon',iconColor: '#FF0000'});
									myMap.geoObjects.add(placemark);
								}
							}
							data += ']}';
							//objectManager.add(data);
						}
					}
				},1000);
			}
		}
	},500);
}
$(document).ready(function()
{
	$(".tabs_content .tbc1").load("/personal/order/make/include/step2_tab1.php");
	$("a.check-step").click(function(e)
	{
		e.preventDefault();
		var url = $(this).attr("href");
		var id = $(this).attr("data-id");
		$.post
		(
			"/personal/order/make/system.php",
			{
				use : 1,
				id : id,
				method : 1
			},
			function(data)
			{
				window.location.href=url;
			}
		);
	});
			
	$("#step2_form .tabs .tab a").click(function()
	{
		$("#step2_form .tabs .tab").removeClass("active");
		$(this).parent().addClass("active");
		$(".tabs_content .tbc").html("");
		
		var id = $(this).attr("tab-id");
		$(".tabs_content .tbc"+id).load("/personal/order/make/include/step2_tab"+id+".php");
	});
	
	
	$(".create_order").click(function(e)
	{
		e.preventDefault();
		$.post("/personal/order/make/system.php" , $("form.inpts").serialize(), function(data)
			{
				console.log(data);
				data = data.split(":");
				data = data[1];
				window.location.href='/personal/order/final/?ORDER_ID='+data;
			});
	});
});