<?
namespace Ipolh\SDEK\Bitrix;

class Tools{
	private static $MODULE_ID  = IPOLH_SDEK;
	private static $MODULE_LBL = IPOLH_SDEK_LBL;

	static function placeErrorLabel($content,$header=false)
	{?>
		<tr><td colspan='2'>
			<div class="adm-info-message-wrap adm-info-message-red">
				<div class="adm-info-message">
					<?if($header){?><div class="adm-info-message-title"><?=$header?></div><?}?>
					<?=$content?>
					<div class="adm-info-message-icon"></div>
				</div>
			</div>
		</td></tr>
	<?}

	static function placeWarningLabel($content,$header=false,$heghtLimit=false,$click=false)
	{?>
		<tr><td colspan='2'>
			<div class="adm-info-message-wrap">
				<div class="adm-info-message" style='color: #000000'>
					<?if($header){?><div class="adm-info-message-title"><?=$header?></div><?}?>
					<?if($click){?><input type="button" <?=($click['id'] ? 'id="'.self::$MODULE_LBL.$click['id'].'"' : '')?> onclick='<?=$click['action']?>' value="<?=$click['name']?>"/><?}?>
						<div <?if($heghtLimit){?>style="max-height: <?=$heghtLimit?>px; overflow: auto;"<?}?>>
						<?=$content?>
					</div>
				</div>
			</div>
		</td></tr>
	<?}
}
?>