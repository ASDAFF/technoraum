<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="spares-search">

    <form action="" method="get">

        <div class="input-group">
            <input type="text" class="form-control" placeholder="¬ведите артикул или название модели дл€ поиска" name="q" value="<?=$arResult["REQUEST"]["QUERY"]?>" size="50" />
            <span class="input-group-btn">
            <button type="submit" class="btn btn-default" value="<?=GetMessage("SEARCH_GO")?>" ><?=GetMessage("SEARCH_GO")?></button>
            <input type="hidden" name="how" value="<?echo $arResult["REQUEST"]["HOW"]=="d"? "d": "r"?>" />
          </span>
        </div><!-- /input-group -->

    </form>

</div>


