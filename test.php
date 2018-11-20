<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");
?>

    <script type="text/javascript">
        var partnerID = "9211473";

        var debug = true;

        var arrProducts = new Array();
        arrProducts[0] = {id : '108', price: '49990', count: '1', type: 'Паропылесосы', name: 'Паропылесос SV 7' };

        DCLoans(partnerID, 'delProduct', false, function(result){
            if (result.status == true) {
                DCLoans(partnerID, 'addProduct', { products : arrProducts }, function(result){
                    if (result.status == true) {
                        DCLoans(partnerID, 'saveOrder', {
                                order : '123',
                                phone: '9161234567',
                                codeTT: '347344637',
                            },
                            function(result){
                                console.log(result);
                            }, debug);
                    }
                }, debug);
            }
        }, debug);

        function DCCheckStatus(result){
            console.log(result);
        }
    </script>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>