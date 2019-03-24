<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="news-list">
	<span><?=GetMessage("CT_SORT_TEXT")?></span>
	<a href="<?=$APPLICATION->GetCurPageParam("sort=RANK_NEWS_ASC", array("sort"), false);?>">
		<?=GetMessage("CT_SORT_ASC")?>
	</a>
    <a href="<?=$APPLICATION->GetCurPageParam("sort=RANK_NEWS_DESC", array("sort"), false);?>">
		<?=GetMessage("CT_SORT_DESC")?>
	</a><br>

	<ul class="toggle-menu">
		<li class="menu-li">
			<a href="<?=$APPLICATION->GetCurPageParam("sort=RANK_NONE", array("sort"), false);?>">
				<?=GetMessage("CT_SORT_NONE")?>
			</a>
		</li>
		<li class="menu-li">
			<a href="<?=$APPLICATION->GetCurPageParam("sort=RANK_THREE", array("sort"), false);?>">
				<?=GetMessage("CT_SORT_THREE")?>
			</a>
		</li>
		<li class="menu-li">
			<a href="<?=$APPLICATION->GetCurPageParam("sort=RANK_FIVE", array("sort"), false);?>">
				<?=GetMessage("CT_SORT_FIVE")?>
			</a>
		</li>
		<li class="menu-li">
			<a href="<?=$APPLICATION->GetCurPageParam("sort=RANK_TEN", array("sort"), false);?>">
				<?=GetMessage("CT_SORT_TEN")?>
			</a>
		</li>
	</ul>

<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
		$arItem["RANK_NEWS2"] = CRatings::GetRatingVoteResult("THEMES", $arItem['ID']);
		$arItem["RANK_NEWS2"] = $arItem["RANK_NEWS2"]["TOTAL_POSITIVE_VOTES"];
		CIBlockElement::SetPropertyValuesEx($arItem["ID"], false, array("RANK_NEWS2" => $arItem["RANK_NEWS2"]));
		$classItem = "false";
	?>
	<div class="div-item">
		<p class="news-item">
			<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
				<a class="div-item-header" href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b>
						<?echo $arItem["NAME"]?></b>
				</a><br />
			<?endif;?>
			<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
				<div class="div-item_img">
					<a class="link-img" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
						<img class="preview_picture" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
							 alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
							 title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"/>
					</a>
				</div>
			<?endif?>
			<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
				<?echo $arItem["PREVIEW_TEXT"];?>
			<?endif;?>
			<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
				<div style="clear:both"></div>
			<?endif?>
			<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
				<a class="div-item_btn" href="<?echo $arItem["DETAIL_PAGE_URL"]?>">Читать дальше</a><br /><br />
			<?endif;?>
		</p>

		<?$APPLICATION->IncludeComponent("bitrix:rating.vote","",
			Array(
				"ENTITY_TYPE_ID" => "THEMES",
				"ENTITY_ID" => $arItem['ID']
			),
			null,
			array("HIDE_ICONS" => "Y")
		);?>

	</div>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>



