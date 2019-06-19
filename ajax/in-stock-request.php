<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$IBLOCK_ID = strip_tags($_REQUEST['ib']);
$ID = array_map(function ($id){
    return strip_tags($id);
}, $_REQUEST['id']);

if(CModule::IncludeModule( 'catalog' ) && count($ID) > 0):?>

    <div id="in-stock-request">

        <table class="table table-striped table-stock">
            <thead>
            <tr>
                <th>Позиция</th>
                <th>Артикул</th>
                <th>Наименование</th>
                <th>Количество в комплекте</th>
                <th>Цена</th>
                <th>Запрос</th>
                <th>Удалить</th>
            </tr>
            </thead>
            <tbody>
            <?
            $arSelect = Array("ID", "IBLOCK_ID","SORT","CATALOG_GROUP_1", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
            $arFilter = Array("IBLOCK_ID" => $IBLOCK_ID,"ID" => $ID, "ACTIVE" => "Y");
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNextElement()):
                $arFields = $ob->GetFields();
                $arProps = $ob->GetProperties();
                ?>
                <tr>
                    <td><?=$arFields['SORT']?></td>
                    <td><?=$arProps['ARTICLE']['VALUE']?></td>
                    <td><?=$arFields['NAME']?></td>
                    <td><?=$arProps['COUNT_COMPLECT']['VALUE']?></td>
                    <td><?=CCurrencyLang::CurrencyFormat($arFields['CATALOG_PRICE_1'],$arFields['CATALOG_CURRENCY_1'])?></td>
                    <td>Запрос наличие</td>
                    <td><a href="#" class="delete-stock-item"><i class="fa fa-times"></i></a></td>
                </tr>
            <?endwhile;?>
            </tbody>
        </table>

        <div class="form-stock">
            <div class="mail-stock">
                <input type="email" placeholder="введите свой e-mail" required>
            </div>
            <div class="btn-stock">
                <a href="javascript:void(0)" onclick="$.fancybox.close( true )" class="close-stock">Добавить деталь</a>
                <a href="javascript:void(0)" class="send-mail-stock">Отправить запрос</a>
            </div>
        </div>

    </div>

<?endif;?>
