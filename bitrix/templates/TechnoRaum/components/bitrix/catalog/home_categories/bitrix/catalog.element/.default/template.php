<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? //print_r($arResult); ?>
<div class="card_page_wrap clearfix">
	<div class="card_page_img">
		<?
		if($arResult["PROPERTIES"]["ARTICLE"]["VALUE"])
		{
			?><p class="card_article">�������: <span><?=$arResult["PROPERTIES"]["ARTICLE"]["VALUE"]?></span></p><?
		}
		?>
		<div class="big_img">
			<?
				if($arResult["DETAIL_PICTURE"]["SRC"])
					$img = $arResult["DETAIL_PICTURE"]["SRC"];
				else
					$img = $arResult["PREVIEW_PICTURE"]["SRC"];
			?>
			<a href="<?=$img?>" class="fancy" rel="card_gal">
				<em></em><img src="<?=$img?>" alt="" />
			</a>
			<?
				$stick = $arResult["PROPERTIES"]["STICKER"]["VALUE_XML_ID"];
				switch($stick)
				{
					case "val1":
						$block = '<span class="hit">'.$arResult["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
						break;
					case "val2":
						$block = '<span class="new">'.$arResult["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
						break;
					case "val3":
						$block = '<span class="action">'.$arResult["PROPERTIES"]["STICKER"]["VALUE"].'</span>';
						break;
					default:
						unset($block);
						break;
				}
				if($block)
					echo '<div class="flag">'.$block.'</div>';
			?>
		</div>
		<div class="card_img_mini_wrap">
			<div class="mini_img mini_img_slider mini_img_scroll modern-skin">
				<ul class="slides clearfix">
				<?
					foreach($arResult["PROPERTIES"]["PHOTO"]["VALUE"] as $img)
					{
						$big_img = CFile::GetPath($img);
						$sm_img = CFile::ResizeImageGet($img, array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true);
						?>
						<li>
							<a href="<?=$big_img?>" class="fancy" rel="card_gal">
								<em></em><img src="<?=$sm_img["src"]?>" alt="" />
							</a>
						</li>
						<?
					}
				?>
				</ul>
			</div>
		</div>
	</div>
	<div class="card_page_specs">
		<div class="the_price">
			<p class="old_price">
				<?
				if($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"] && $arResult["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"])
				{
					echo $arResult["PROPERTIES"]["OLD_PRICE_VAL"]["VALUE"]." &#8381;";
				} 
				?>
			</p>
			<p class="price"><span><?=$arResult["PRICES"]["price"]["VALUE"]?> &#8381;</span></p>
		</div>
		<div class="button_wrap">
			<a class="button add_to_cart_button" href="<?=$arResult["BUY_URL"]?>">�������� � �������</a>
			<a class="fancy button one_click_button" href="#one_click_popup">������ � ���� ����</a>
		</div>
		<?
		if($arResult["PRODUCT"]["QUANTITY"])
		{
			?><p class="in_store">�� ������</p><?
		}
		else
		{
		?><p><font color='red'>X</font> ����������� �� ������</p><?
		}
		?>
		<ul>
			<li>���������� �������� �� ����������</li>
			<li>���������� ����������� � ���� � ������������</li>
			<li>���������� ������������ ������� � ������� �� ���</li>
		</ul>
		<label class="compare"><input type="checkbox" name="compare" checked="checked" /><span>�������� � ���������</span></label>
		<div class="card_consult_text">
			<p>
				���� ��� ��������� ������ � ������ ��� ������������ � �������<br/>
				�� ������� ����� 8 800 250 13 08 ��� <a class="fancy" href="#callback_popup">�������� �������� ������</a>
			</p>
		</div>
	</div>
</div>
<div class="card_page_description clearfix">
	<div class="card_page_properties">
		<p class="title">��������������</p>
		<?
		$i=1;
		while(true)
		{
			if($arResult["PROPERTIES"]["DETAIL_P".$i])
			{
				?>
					<p>
						<b><?=$arResult["PROPERTIES"]["DETAIL_P".$i]["NAME"]?></b>
						<i><?=$arResult["PROPERTIES"]["DETAIL_P".$i]["VALUE"]?></i>
					</p>
				<?
			}
			else
				break;
			$i++;
		}
		?>
	</div>
	<div class="card_page_descr">
		<p class="title">��������</p>
		<div class="text_toggling_div" data-start-height="260">
			<p><?=$arResult["DETAIL_TEXT"]?></p>
		</div>
		<a href="#" class="read_more_toggler"><span>�������� �����</span></a>
	</div>
</div>