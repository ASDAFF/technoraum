$( function() {

    $("input[autocomplete='tel']").mask("+7 (999) 999 99-99");

    $( "#tabs" ).tabs();
    $(".checkbox-toggle").tinyToggle();

    $(".compare-checkbox").tinyToggle({
        onCheck: function() {
            var id = $("input[name='product_id']").val();
            $.post("/compare/index.php?action=ADD_TO_COMPARE_LIST&id="+id);
            $.post("/system.php" , {action : "add" , id : $(this).attr("vl")});
        },
        onUncheck: function() {
            var id = $("input[name='product_id']").val();
            $.post("/compare/index.php?action=DELETE_FROM_COMPARE_LIST&id="+id);
            $.post("/system.php" , {action : "del" , id : id});
        },
    });

    $(".card-scroll").anchorlink({
        offsetTop : -150,
        timer : 800,
        scrollOnLoad : false,
        afterScroll : function(data){
            $( ".ui-tabs" ).tabs( "option", "active", $(this).data("id") );
        }
    });

    var partnerID = "9211473";
    var debug = false;

    if(arrProducts) {

        DCLoans(partnerID, 'getPayment', { products : arrProducts }, function(result){
            if(result.status == true){
                $('#getPaymentDc').text("от " + result.payments[arrProducts[0].id].payment + " руб./мес.");
            }
        });

        $("#phoneCredit").mask("+7 (999) 999 99-99");
        var dialog, form,
            fioCredit = $( "#nameCredit" ),
            phoneCredit = $( "#phoneCredit" ),
            locationCredit = $( "#locationCredit" ),

            allFields = $( [] ).add( name ).add( phoneCredit ),
            tips = $( ".validateTips" );

        function updateTips( t ) {
            tips.text( t );
        }

        function checkRegexp( o, regexp, n ) {
            if ( !( regexp.test( o.val() ) ) ) {
                o.addClass( "ui-state-error" );
                updateTips( n );
                return false;
            } else {
                return true;
            }
        }
        function checkLength( o, n ) {
            if ( o.val().length < 1 ) {
                o.addClass( "ui-state-error" );
                updateTips( "Поле " + n + " обязательное." );
                return false;
            } else {
                return true;
            }
        }

        function addUser() {
            var valid = true;
            allFields.removeClass( "ui-state-error" );
            valid = valid && checkRegexp( fioCredit, /^[А-Я]([а-я])+$/i, "Только русские буквы без пробелов." );
            valid = valid && checkLength( phoneCredit, "Ваш телефон" );
            if ( valid ) {
                var Order = {
                        firstName: fioCredit.val(),
                        order : arrProducts[0].id_order,
                        phone: phoneCredit.val().replace(/\D+/g,"").slice(1),
                        codeTT: locationCredit.val(),
                    };
                dialog.dialog( "close" );
                DCLoans(partnerID, 'delProduct', false, function(result){
                    if (result.status == true) {
                        DCLoans(partnerID, 'addProduct', { products : arrProducts }, function(result){
                            if (result.status == true) {
                                DCLoans(partnerID, 'saveOrder', Order,
                                    function(result){}, debug);
                            }
                        }, debug);
                    }
                }, debug);
            }
            return valid;
        }
        dialog = $( "#dialog-form" ).dialog({
            autoOpen: false,
            height: 400,
            width: 500,
            modal: true,
            buttons: {
                "Отправить": addUser,
                "Выйти": function() {
                    dialog.dialog( "close" );
                }
            },
            close: function() {
                form[ 0 ].reset();
                allFields.removeClass( "ui-state-error" );
            }
        });

        form = dialog.find( "form" ).on( "submit", function( event ) {
            event.preventDefault();
            addUser();
        });

        $( "#getCredit" ).on( "click", function() {
            dialog.dialog( "open" );
        });
    }

    $(".form_one_buy").submit(function(e){
        e.preventDefault();
        $.post("/include/order_buy.php",$(this).serialize(),function(data){
            if(data){
                $(".open_thanks").trigger("click");
            }
        });
    });

});

function DCCheckStatus(result){
    console.log(result);
}