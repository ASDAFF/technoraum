$(document).ready(function(){

    $(".request-a-price").click(function(){
        var name = $(this).attr("data-name");
        $(".rform").attr("data-name",name);
    });
    $(".rform").submit(function(e){
        e.preventDefault();
        var name = $(this).attr("data-name");
        $.post("/mail.php",$(this).serialize() + "&name_page=" + name,function(data){
            $(".fancybox-close").trigger("click");
            setTimeout(function(){
                $(".open_thanks").trigger("click");
            },500);
        });
    });

    $(".mform").submit(function(e){
        e.preventDefault();
        $.post("/mail.php",$(this).serialize(),function(data){
            $(".fancybox-close").trigger("click");
            setTimeout(function(){
                $(".open_thanks").trigger("click");
            },500);
        });
    });

    $(".subscribe").submit(function(e){
        e.preventDefault();
        $.post("/mail.php",$(this).serialize(),function(data){
            if(data)$(".sub_error").trigger("click");
            else
                $(".open_thanks2").trigger("click");
        });
    });

    $(".fancy.button.call_me").click(function(){
        var name=$("input[name='product_name']").val();
        $(".call_me_form input[name='product_name']").val(name);
    });

    $(".call_me_form").submit(function(e){
        e.preventDefault();
        $.post("/mail.php",$(this).serialize(),function(data){
            $(".open_thanks").trigger("click");
        });
    });

    $(".create_order").submit(function(e){
        e.preventDefault();
        $.post("/order.php?form=1",$(this).serialize(),function(data){
            data=data.split("order_id:");
            var order_id=data[1];
            window.location.href=window.location.href+"?ORDER_ID="+order_id;
        });
    });

    $(".login_form").submit(function(e){
        e.preventDefault();
        var btn_text=$(".login_form input[type='submit']").val();
        $(".login_form input[type='submit']").val("").addClass("loading");
        $.post("/login.php",$(this).serialize(),function(data){
            if(data==0){$(".login_form .error").html("Неправильный e-mail или пароль<br>");
                $(".login_form input[type='submit']").val(btn_text).removeClass("loading");
            }else{
                var url=window.location.href;window.location.href=url;
            }
        });
    });

    $(".reg_form").submit(function(e){
        e.preventDefault();
        var btn_text=$(".reg_form input[type='submit']").val();
        $(".reg_form input[type='submit']").val("").addClass("loading");
        var pass=$(".reg_form input[name='password']").val();
        var rpass=$(".reg_form input[name='rpassword']").val();
        if(pass==rpass){
            $.post("/registration.php",$(this).serialize(),function(data){
                if(data.length>10){
                    $(".reg_form input[type='submit']").val(btn_text).removeClass("loading");
                    $(".reg_form .error").html(data);
                }else{
                    $(".fancybox-close").trigger("click");
                    setTimeout(function(){
                        $(".reg_ok").trigger("click");
                        setTimeout(function(){
                            var url=window.location.href;
                            window.location.href=url;
                        },2500);
                    },2000);
                }
            });
        }else{
            $(".reg_form input[type='submit']").val(btn_text).removeClass("loading");
            $(".reg_form .error").html("Введенные пароли не совпадают<br>");
        }
    });

});