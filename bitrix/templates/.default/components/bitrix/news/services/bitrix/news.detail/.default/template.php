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
<div class="news-detail">
  <?
    if($arResult["DETAIL_PICTURE"])
    {
      ?>
        <a href="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" class="fancy"><img style="width:100%;height:initial;"
          class="detail_picture"
          border="0"
          src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
          width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
          height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
          alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
          title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
        /></a>
      <?
    }

      $form = '</p><div class="popup callback_popup" id="callback_popup" style="display: block;">
      <form method="post" class="mform">
        <div class="the_form">                    
          <input type="hidden" name="form_id" value="4">  
                      <p class="form_title">Есть вопросы?</p>
                      
                      <div class="the_form_div">
                            <label>Имя</label>
                            <input required="" type="text" name="name" placeholder="Кузнецов Александр Сергеевич">
                      </div>
                          
                      <div class="the_form_div">
                            <label>Сотовый телефон</label>
                            <input required="" type="text" name="tel" placeholder="+7 (9ХХ) ХХХ-ХХ-ХХ">
                      </div>
                      <div class="the_form_div the_form_div_accept">
                        <label><div class="ez-checkbox ez-checked"><input required="" type="checkbox" name="check" checked="checked" class="ez-hide"></div><span>Я согласен с <a href="/soglasie-na-obrabotku-personalnykh-dannykh/" target="_blank">условиями использования</a> моих персональных данных.</span></label>
                      </div>
                      <div class="the_form_div the_form_div_submit clearfix">
                         
                        <input type="submit" name="submit1" value="Отправить">
                                             
                      </div>                    

        </div>
      </form>
    </div>';

    $arResult["DETAIL_TEXT"] = str_replace("{{FORM}}" , $form , $arResult["DETAIL_TEXT"]);
  ?>
  <div style="clear:both;"></div>
  <p style="padding-top: 30px;"><?=$arResult["DETAIL_TEXT"]?></p>
  <?


  if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
  {
    ?>
    <div class="news-detail-share">
      <noindex>
      <?
      $APPLICATION->IncludeComponent("bitrix:main.share", "", array(
          "HANDLERS" => $arParams["SHARE_HANDLERS"],
          "PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
          "PAGE_TITLE" => $arResult["~NAME"],
          "SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
          "SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
          "HIDE" => $arParams["SHARE_HIDE"],
        ),
        $component,
        array("HIDE_ICONS" => "Y")
      );
      ?>
      </noindex>
    </div>
    <?
  }
  ?>
  <div style="display:flex">
    <a class="button fancy" style="width:auto;padding:0 20px" href="#consult_popup">Получить бесплатную консультацию</a>
  </div>
</div>