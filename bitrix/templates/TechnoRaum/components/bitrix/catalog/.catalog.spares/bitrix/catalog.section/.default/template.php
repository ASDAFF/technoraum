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

<? if (!empty($arResult['ITEMS'])):?>

    <? if($arResult['UF_DETAIL_SPARES']): ?>
    <div class="row">
        <div class="col-md-12">
            <img src="<?=CFile::GetPath($arResult['UF_DETAIL_SPARES']);?>" id="zoom_dt" width="100%">
        </div>
    </div>
    <?endif;?>

    <div class="row">
        <div class="col-md-4">
            <div class="option-image" style="background: url(<?=SITE_TEMPLATE_PATH?>/img/arrow.jpg) no-repeat left 4px">
                <div class="o-title">Управление изображением</div>
                <div class="o-body">
                    Для увеличения наведите курсором на изображение и при необходимости дополнительного увеличения воспользуйтесь прокруткой колесика мышки.
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="option-image" style="background: url(<?=SITE_TEMPLATE_PATH?>/img/arrow.jpg) no-repeat left 4px">
                <div class="o-title">Есть вопросы?</div>
                <div class="o-body">
                    <a href="/contacts/" target="_blank">Спросите нашу команду</a>
                </div>
            </div>
        </div>
        <? if($arResult['UF_DETAIL_SPARES']): ?>
        <div class="col-md-4">
            <div class="option-image" style="background: url(<?=SITE_TEMPLATE_PATH?>/img/arrow.jpg) no-repeat left 4px">
                <div class="o-title">Скачать деталировку</div>
                <div class="o-body">
                    <a href="<?=CFile::GetPath($arResult['UF_DETAIL_SPARES']);?>" target="_blank">
                        <img src="<?=SITE_TEMPLATE_PATH?>/img/jpg.jpg" alt="Скачать деталировку">
                    </a>
                </div>
            </div>
        </div>
        <?endif;?>
    </div>


    <div class="row">

        <div class="col-md-12">

            <table class="table table-striped section-element">
                <thead>
                <tr>
                    <th>Позиция</th>
                    <th>Артикул</th>
                    <th>Наименование</th>
                    <th>Количество в комплекте</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Срок отгрузки</th>
                    <th>Купить</th>
                </tr>
                </thead>

                <tbody>
                <? foreach ($arResult['ITEMS'] as $arItem): ?>
                    <tr>
                        <td><?=$arItem['SORT']?></td>
                        <td><?=$arItem['PROPERTIES']['ARTICLE']['VALUE']?></td>
                        <td><?=$arItem['NAME']?></td>
                        <td><?=$arItem['PROPERTIES']['COUNT_COMPLECT']['VALUE']?></td>
                        <td><?=$arItem['PRICES']['price']['PRINT_DISCOUNT_VALUE']?></td>
                        <td id="spares_count_<?=$arItem['ID']?>"><input type="text" value="1" size="1"></td>
                        <td>
                            <div class="vertic">
                                <div class="zaprosc1">
                                    <input type="checkbox" value="<?=$arItem['ID']?>" data-ib="<?=$arItem['IBLOCK_ID']?>">
                                    <div class="zaprosbott">
                                        <div class="request-stock">Запрос наличия</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="spares-cart">
                            <a href="javascript:void(0)" onclick="addToBasket2(<?=$arItem['ID']?>, $('#spares_count_<?=$arItem['ID']?> input').val(),this);">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                в корзину
                            </a>
                        </td>
                    </tr>
                <?endforeach;?>
                </tbody>
            </table>

        </div>

    </div>

    <? echo $arResult["NAV_STRING"]; ?>

<?endif;?>
