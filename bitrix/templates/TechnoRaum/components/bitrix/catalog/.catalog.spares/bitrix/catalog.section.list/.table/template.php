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

<? if (!empty($arResult['SECTIONS'])):?>

<div class="row">

    <div class="col-md-12">
        <table class="table table-striped" data-container="body" data-toggle="popover" data-placement="top" data-content="¬ыберите модель инструмента или оборудовани€, дл€ которого вам необходимы запасные части">
            <tbody>
            <? foreach ($arResult['SECTIONS'] as &$arSection): ?>
                <tr>
                    <td><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a></td>
                </tr>
            <?endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<? endif; ?>


