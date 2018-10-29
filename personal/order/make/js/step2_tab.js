$(document).ready(function()
{
	ymaps.ready(function()
	{
		CreateMap($("input[name='del_method']").val());
	});
	$(".city_title").click(function(e)
	{
		e.preventDefault();
		var city = $(this).text();
		$("input[name='city']").val(city);
		$(this).parent().parent().children("li").removeClass("active");
		$(this).parent().addClass("active");
		
		$("input[name='map_x']").val($(this).parent().find("input[name='item_map_x']").val());
		$("input[name='map_y']").val($(this).parent().find("input[name='item_map_y']").val());
		$("input[name='point_name']").val($(this).parent().find("input[name='item_point_name']").val());
		$("input[name='point_phone']").val($(this).parent().find("input[name='item_point_phone']").val());
		$("input[name='point_time']").val($(this).parent().find("input[name='item_point_time']").val());
		$("input[name='point_day']").val($(this).parent().find("input[name='item_point_day']").val());
		$("input[name='point_map']").val($(this).parent().find("input[name='item_point_map']").val());
		
		CreateMap($("input[name='del_method']").val());
	});
});