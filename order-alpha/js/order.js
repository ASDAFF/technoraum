function getCityID(city)
{
	$.ajax
	({
		url: "http://api.cdek.ru/city/getListByTerm/jsonp.php?callback=?",
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
							// �������� ������ ��������� ��������������
							var firstGeoObject = res.geoObjects.get(0);
							var cords = firstGeoObject.geometry.getCoordinates();
								
							var temp = '';
							if(cords)
							{
								var map_y = cords[0];
								var map_x = cords[1];
								$("input[name='temp1']").val($("input[name='temp1']").val() + "|" + map_y + " " + map_x + "|");
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
						var cords = [];
						$("input[name='temp1']").val('');
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
									line = line.split(" ");
									
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
								line = line.split(" ");
								
								center_y = line[0];
								center_x = line[1];
							}
						}
						
						city = $("input[name='city']").val();
						nz = 12;
						if(!city)
							nz = 8;
						var myMap = new ymaps.Map('map', 
						{
							center: [center_y, center_x],
							zoom: nz
						}, 
						{
							searchControlProvider: 'yandex#search'
						}),
						objectManager = new ymaps.ObjectManager
						({
							// ����� ����� ������ ����������������, ���������� �����.
							clusterize: true,
							// ObjectManager ��������� �� �� �����, ��� � �������������.
							gridSize: 32,
							clusterDisableClickZoom: true
						});
						
						// ����� ������ ����� ��������� �������� � ���������,
						// ��������� � �������� ���������� ObjectManager.
						objectManager.objects.options.set('preset', 'islands#greenDotIcon');
						objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
						myMap.geoObjects.add(objectManager);
						
						var data = '{"type":"FeatureCollection","features":[';
						var city = $("input[name='city']").val();
						
						if(point_name)
						{
							if(point_name.length > 2)
							{
								for(i=0,first=0;i<=point_name.length-1;i++,first++)
								{
									var map = cords[i];
									if(map)
									{
										map = map.split(" ");
									
										if(first != 0)
											data += ',';
									
											data += '\
											{"type":"Feature","id":'+i+',"geometry":{"type":"Point","coordinates":['+map[0]+', '+map[1]+']},\
												"properties" : \
												{\
													"balloonContentHeader": "<div class=\'pp_block1\'><p class=\'tt\'>�����</p><p>'+point_name[i]+'</p></div>", \
													"balloonContentBody": "<div class=\'pp_block2\'><p class=\'tt\'>�������</p><p>8-918-325-70-02</p><p class=\'tt\'>����� ������</p><p>����, 9:00�19:00<br>����, 10:00�17:00</p>",\
													"balloonContentFooter": "<form class=\'pp_form\' method=\'POST\' action=\'/personal/order/make/\'><input type=\'hidden\' name=\'city_id\' value=\''+city_id+'\' /><input type=\'hidden\' name=\'change_dmethod\' value=\''+dil+'\' /><input type=\'hidden\' name=\'dil_address\' value=\''+$("input[name='city']").val() + ', ' + point_name[i] +'\' /><input type=\'hidden\' name=\'change_step\' value=\'3\' /><input type=\'submit\' value=\'������ �����\'></form>" \
												}\
											}';
									}
									
								}
							}
							else
							{
								var map = cords[0];
								if(map)
								{
									map = map.replace("|", "");
									map = map.split(" ");
									

									data += '\
									{"type":"Feature","id":1,"geometry":{"type":"Point","coordinates":['+map[0]+', '+map[1]+']},\
										"properties" : \
										{\
											"balloonContentHeader": "<div class=\'pp_block1\'><p class=\'tt\'>�����</p><p>'+point_name[0]+'</p></div>", \
											"balloonContentBody": "<div class=\'pp_block2\'><p class=\'tt\'>�������</p><p>8-918-325-70-02</p><p class=\'tt\'>����� ������</p><p>����, 9:00�19:00<br>����, 10:00�17:00</p>",\
											"balloonContentFooter": "<form method=\'POST\' action=\'/personal/order/make/\'><input type=\'hidden\' name=\'change_step\' value=\'3\' /><input type=\'submit\' value=\'������ �����\'></form>" \
										}\
									}';
								}
							}
							data += ']}';
							objectManager.add(data);
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
	$("#step1_form").submit(function(e)
	{
		var obj = $(this);
		e.preventDefault();
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
					$(".step_form .error").text("������������ � ����� e-mail ������� ��� ����������. ������� ��� ����� ������� ������� ��� ����������� ������ e-mail �����");
				else
				{
					$.post
					(
						"system.php",
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
			"system.php",
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
	
	$(".create_order").click(function()
	{
		$.post("system.php" , {use : 1 , method : 6}, function(data)
			{
				data = data.split(":");
				data = data[1];
				window.location.href='/personal/order/make/?ORDER_ID='+data;
			});
	});
});