<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="row">

    <? foreach ($arResult['SECTIONS'] as &$arSection): ?>

        <div class="col-md-6">
            <div class="item-sections">
               <img class="sections-img" src="<? echo $arSection['PICTURE']['SRC']; ?>" width="100">
               <a class="section-title" href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a>
            </div>
        </div>

    <?endforeach; ?>

</div>


