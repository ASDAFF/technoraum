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
                        value: item.cityName,
                        id: item.id
                    }
                }));
            }
        });
    },
    minLength: 1,
    select: function(event,ui) {
        console.log("Yep!");
    }
});