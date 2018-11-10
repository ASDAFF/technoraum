$( function() {

    $( "#tabs" ).tabs();
    $(".checkbox").tinyToggle();

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

});