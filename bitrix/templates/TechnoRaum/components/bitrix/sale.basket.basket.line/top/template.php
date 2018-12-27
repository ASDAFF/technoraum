<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @global string $componentPath
 * @global string $templateName
 * @var CBitrixComponentTemplate $this
 */

$text = $arResult["PRODUCTS"];
$text = str_replace("вашей " , "", $text);
$summ = $arResult["TOTAL_PRICE"];
?>
<span class='card_count'><?=$arResult["NUM_PRODUCTS"]?></span>
<input type='hidden' name='total_cart_summ' value='<?=$summ?>' />
