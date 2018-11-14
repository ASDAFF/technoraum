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
$com_path = '/bitrix/components/nbrains/slider/';
$this->addExternalCss($com_path."front/css/masterslider.css");
$this->addExternalJS($com_path."front/js/jquery-3.1.1.min.js");
$this->addExternalJS($com_path."front/js/masterslider.js");

?>
<div class="nbrains-slider-block" style="max-width: <?=$arParams['WIDTH']?>px">
	<div id="P_MS<?=$arResult['ID'];?>" class="master-slider-parent ms-parent-id-62">
		<!-- MasterSlider Main -->
		<div id="MS<?=$arResult['ID']?>" class="master-slider ms-skin-light-3" >
			<?foreach($arResult["ITEMS"] as $arItem):?>
			<div  class="ms-slide" data-delay="<?=$arParams['TIME_SLIDE']?>" <? if(!empty($arItem['CODE'])): ?>onclick="javascript:location.href='<?=$arItem['CODE']?>'" <? endif; ?> data-fill-mode="fill">
				<? if(strlen($arItem["PREVIEW_TEXT"]) > 1){ ?>
				<div class="ms-slider-text-box" style="right: <?=$arParams['RIGHT_PX_TEXT']?>px;top:<?=$arParams['TOP_PX_TEXT']?>px"><?=trim(strip_tags($arItem["PREVIEW_TEXT"]));?></div>
				<? } ?>

				<img src="<?=$com_path?>front/css/blank.gif" alt="" title="" data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>" />

			</div>
			<?endforeach;?>
		</div>
		<!-- END MasterSlider Main -->
	</div>
</div>
<script>
	"use strict";
	var masterslider_<?=$arResult['ID']?> = new MasterSlider();
	// slider controls
	masterslider_<?=$arResult['ID']?>.control('arrows'     ,{ autohide:<?=$arParams['HIDDEN_ARROWS']?>, overVideo:true  });
	masterslider_<?=$arResult['ID']?>.control('bullets'    ,{ autohide:false, overVideo:true, dir:'h', align:"<?=$arParams['BTN_SLIDE_CONTROL']?>", space:5 , margin:10  });
	masterslider_<?=$arResult['ID']?>.control('timebar'    ,{ autohide:false, overVideo:true, align:'<?=$arParams['PROGRESS_BAR_PLACE']?>', color:'#<?=$arParams['PROGRESS_BAR_COLOR']?>', width:<?=$arParams['PROGRESS_BAR_HEIGHT']?> });

	// slider setup
	masterslider_<?=$arResult['ID']?>.setup("MS<?=$arResult['ID']?>", {
		width           : <?=$arParams['WIDTH']?>,
		height          : <?=$arParams['HEIGHT']?>,
		minHeight       : 0,
		space           : 0,
		start           : 1,
		grabCursor      : true,
		swipe           : true,
		mouse           : true,
		keyboard        : false,
		layout          : "boxed",
		wheel           : false,
		autoplay        : true,
		instantStartLayers:false,
		loop            : true,
		shuffle         : false,
		preload         : 0,
		heightLimit     : true,
		autoHeight      : false,
		smoothHeight    : true,
		endPause        : false,
		overPause       : true,
		fillMode        : "fill",
		centerControls  : true,
		startOnAppear   : false,
		layersMode      : "center",
		autofillTarget  : "",
		hideLayers      : false,
		fullscreenMargin: 0,
		speed           : 50,
		dir             : "h",
		parallaxMode    : 'swipe',
		view            : "basic"
	});


	window.masterslider_instances = window.masterslider_instances || [];
	window.masterslider_instances.push( masterslider_<?=$arResult['ID']?> );


</script>