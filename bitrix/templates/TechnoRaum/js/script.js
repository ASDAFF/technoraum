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

        var arrProd = [];
        $('.direct-credit-section').each(function(li, el){
            arrProd[li] = JSON.parse($(el).attr("arrProducts"));
        });
        DCLoans(partnerID, 'getPayment', { products : arrProd }, function(result){
            if(result.status == true){
                $.each(result.payments, function( index, value ) {
                    if(value){
                        $('.getPaymentDcSection' + index).html(value.payment + "<span>���/���</span>");
                    }
                });
            }
        });

        DCLoans(partnerID, 'getPayment', { products : arrProducts }, function(result){
            if(result.status == true){
                $('#getPaymentDc').html(result.payments[arrProducts[0].id].payment + "<span>���/���</span>");
                var paymentAll = 0;
                $.each(result.payments, function( index, value ) {
                    if(value){
                        paymentAll += value.payment;
                    }
                });
                $('#getPaymentDcAll').html(paymentAll + "<span>���/���</span>");
            }
        });

        $("#phoneCredit").mask("+7 (999) 999 99-99");
        var dialog, form,
            fioCredit = $( "#nameCredit" ),
            phoneCredit = $( "#phoneCredit" ),
            locationCredit = $( "#locationCredit" ),
            sectionProd = $( "#sectionProd" ),
            rule = $( "#rule" ),

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
                updateTips( "���� " + n + " ������������." );
                return false;
            } else {
                return true;
            }
        }
        function checkCheckbox( o, n ) {
            if (!o.is( ":checked" )) {
                o.addClass( "ui-state-error" );
                updateTips( "���� " + n + " ������������." );
                return false;
            } else {
                return true;
            }
        }

        function addUser() {
            var valid = true;
            allFields.removeClass( "ui-state-error" );
            valid = valid && checkRegexp( fioCredit, /^[�-�]([�-�])+$/i, "������ ������� ����� ��� ��������." );
            valid = valid && checkLength( phoneCredit, "��� �������" );
            valid = valid && checkCheckbox( rule, "����������� �������� �� ��������� ������������ ������." );
            if ( valid ) {
                if(arrProducts[0] == undefined){
                    arrProducts[0] = JSON.parse(sectionProd.val());
                }
                var Order = {
                        firstName: fioCredit.val(),
                        order : arrProducts[0].id_order,
                        phone: phoneCredit.val().replace(/\D+/g,"").slice(1),
                        codeTT: locationCredit.val(),
                    };

                $.post("/include/mail_credit.php",{
                    firstName: fioCredit.val(),
                    phone: phoneCredit.val().replace(/\D+/g,"").slice(1),
                    shop: locationCredit.find('option:selected').text(),
                },function(data){});

                dialog.dialog( "close" );
                yaCounter51314392.reachGoal('KREDIT',function(){console.log('goal KREDIT');});
                console.log(arrProducts,Order);
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
            height: 440,
            width: 500,
            modal: true,
            buttons: {
                "���������": addUser,
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

        $( "body" ).on( "click","#getCredit", function() {
            dialog.dialog( "open" );
        });

        $( ".direct-credit-section" ).on( "click", function() {
            var arrProducts = $(this).attr("arrProducts");
            $("#dialog-form").find("input[name='sectionProd']").val(arrProducts);
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


    $('.custom-form .c-input select').change(function(){
        $('.custom-form .c-input textarea').attr('placeholder',$(this).find('option:selected').attr('data-text'));
    });


});

function DCCheckStatus(result){
    console.log(result);
}