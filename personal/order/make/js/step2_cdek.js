$(function() {
  $("#city").autocomplete({
    source: function(request,response) {
      $.ajax({
        url: "https://api.cdek.ru/city/getListByTerm/jsonp.php?callback=?",
        dataType: "jsonp",
        data: {
        q: function () { return $("#city").val() },
        name_startsWith: function () { return $("#city").val() }
        },
        success: function(data) {
          response($.map(data.geonames, function(item) {
            return {
              label: item.name,
              value: item.name,
              id: item.id
            }
          }));
        }
      });
    },
    minLength: 1,
    select: function(event,ui) {
    //console.log("Yep!");
    $('#receiverCityId').val(ui.item.id);
    }
  });
})

function radio_change(obj)
{
	var city = $(".nstep input[name='city']").val();
	var iclass = obj.attr("class");
	var text = obj.next(".name").text();
	var price = obj.closest(".row").find(".col2.item div").text();
	if(price.length)
	{
		price = price.split(" ");
		price = price[0];
		$("input[name='del_price']").val(price);
	}



	text = text.replace(" " , "");
	text = text.replace("." , ". ");
	text = text.replace("," , ", ");
								
	iclass = iclass.replace("radio-btn " , "");
	iclass = iclass.replace("check" , "");
	iclass = iclass.replace(" ", "");
								
	$(".radio-btn."+iclass).removeClass("check");
	obj.addClass("check");
	$(".nstep input[name='dil_address']").val(city + ", " + text);
}
$(document).ready(function()
{
	$("form#cdek").submit(function(e)
	{
		e.preventDefault();
		var i = 0;
		$(".select_dil").find(".row").each(function()
		{
			if(i != 0)
				$(this).detach();
			i++;
		});
		
		var stocks = [435, 1064, 1251];
		
		for(i=1;i<=3;i++)
		{
			$(this).find("input[name='senderCityId']").val(stocks[i-1]);
			$.post("/personal/order/make/system.php" , $(this).serialize(), function(data)
			{
				data = data.split("|");
				if(data[0] == "ok")
				{
					if(data[4] != data[5])
					{
						var day = "От "+data[4]+" до "+data[5];
						if(data[5] <= 20)
							day += " дней"
						else if(data[5] == 21)
							day += " дня";
						else if(data[5] == 31)
							day += "дня";
					}
					else
					{
						var day = data[4];
						if(data[4] == 1)
							day += " день"
						else if(data[4] < 5)
							day += " дня";
						else if(data[4] < 20)
							day += " дней";
						else if(data[4] == 21)
							day += " день";
						else if(data[4] < 30)
							day += " дней";
						else if(data[4] == 31)
							day += " день";
					}
					
					$(".select_dil").find(".row").filter(":last-child").after("\
					<div class='row'>\
						<div class='col1 item'>\
							<div style='display:flex'>\
								<div onClick='radio_change($(this));' class='radio-btn btn2' data-value='"+i+"'>\
									<div></div>\
								</div>\
								<div class='name' style='display:flex;padding-left:15px'>\
									<a><?=$item['address']?></a>\
								</div>\
							</div>\
							<div><img src='"+data[1]+"'/></div>\
							<div>"+data[2]+"</div>\
						</div>\
						<div class='col2 item'><div>"+data[3]+" руб.</div></div>\
						<div class='col3 item'><div>"+day+"</div></div>\
					</div>");
					$("#dil_form").fadeIn();
				}
				else
				{
					alert("ошибка");
				}
			});
		}
	});
	
});
