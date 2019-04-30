$( function() {

    $current_city = $("#combobox").val();
    $items = $('#map-data').data('items');

    $('#tabs-contact').tabs({
            activate: function( event, ui ) {
                if(ui.newTab.attr('aria-controls') == 'service' || ui.newTab.attr('aria-controls') == 'shop'){
                    $('#service ymaps, #shop ymaps').remove();
                    ymaps.ready(initMap);
                }
            }
        });

    $( "#combobox" ).combobox({
        select: function (event, ui) {
            showCity($items,$(this).val());
            $('#service ymaps, #shop ymaps').remove();
            ymaps.ready(initMap);
        }
    });

    showCity($items,$current_city);
    ymaps.ready(initMap);

});

function showCity(items, current_city){

    var item_shop = "";
    var item_service = "";
    $.each(items, function(key, value){

        if(value.PROPERTIES.CITY.VALUE_XML_ID == current_city){

            if(in_array('SHOP',value.PROPERTIES.TYPE.VALUE_XML_ID)){
                item_shop += renderTemplate('item-temp', {
                    pid: value.ID,
                    cord: value.PROPERTIES.PLACEMARK.VALUE,
                    name: value.NAME,
                    phone: value.PROPERTIES.PHONE.VALUE,
                    mode: value.PROPERTIES.MODE.VALUE,
                });
            }

            if(in_array('SERVICE',value.PROPERTIES.TYPE.VALUE_XML_ID)){
                item_service += renderTemplate('item-temp', {
                    pid: value.ID,
                    cord: value.PROPERTIES.PLACEMARK.VALUE,
                    name: value.NAME,
                    phone: value.PROPERTIES.PHONE.VALUE,
                    mode: value.PROPERTIES.MODE.VALUE,
                });
            }
        }
    });
    $('#shop').find('.shop-list').html(item_shop);
    $('#service').find('.shop-list').html(item_service);
}

function initMap() {

        var arPlaceMark = [];
        var id_block = $(".ui-state-active").attr('aria-controls');

        $('.alert').remove();
        if(!$('#'+ id_block +' .shop').length){
            $('#'+ id_block +' .region-shop').prepend('<div class="alert alert-success" role="alert">В данном городе нас пока что нет.</div>');
            return false;
        }

        var id_map = id_block + "_map";
        var first_cord = $('#'+id_block+' .shop:first-child').attr('data-cord').split(',');

        var myMap = new ymaps.Map(id_map, {
            center: first_cord,
            zoom: 11
        }, {
            searchControlProvider: 'yandex#search'
        });

        collection = new ymaps.GeoObjectCollection(null);
        myMap.geoObjects.add(collection);

        $( '#'+ id_block +' .shop' ).each(function(key, index ) {
            var center = $( this ).attr('data-cord').split(',');
            var content = $(this).html();

            var placemark = new ymaps.Placemark(center, { balloonContent: content },{preset: 'islands#nightDotIcon'});
            var dataPid = $( this ).attr('data-pid');
            arPlaceMark[dataPid] = placemark;
            // Добавляем метку в коллекцию.
            collection.add(placemark);

            placemark.events.add('click', function () {
                $('.shop').removeClass('active');
                $('.shop[data-pid="'+ dataPid +'"]').addClass('active');
            });

        });

        $('#'+ id_block +' .shop').bind('click', function (event) {
            $('.shop').removeClass('active');
            $(this).addClass('active');
            var id_mark = $(this).attr('data-pid');
            if (!arPlaceMark[id_mark].balloon.isOpen())
                arPlaceMark[id_mark].balloon.open();

            return $(event.target).is("A");
        });



}

function in_array(needle, haystack, strict) {

    var found = false, key, strict = !!strict;

    for (key in haystack) {
        if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
            found = true;
            break;
        }
    }
    return found;
}


function renderTemplate(name, data) {
    var template = document.getElementById(name).innerHTML;

    for (var property in data) {
        if (data.hasOwnProperty(property)) {
            var search = new RegExp('{' + property + '}', 'g');
            template = template.replace(search, data[property]);
        }
    }
    return template;
}


