<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @global string $componentPath
 * @global string $templateName
 * @var CBitrixComponentTemplate $this
 */

$text = $arResult["PRODUCTS"];
$text = str_replace("����� " , "", $text);
$summ = $arResult["TOTAL_PRICE"];
?>
<?=$arResult["NUM_PRODUCTS"]?>::::<div class="cart_count"><?=$text?></div><div class="cart_summ">�� ����� <?=$summ?></div>