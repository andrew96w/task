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
//$this->setFrameMode(true);
?>

<div class="news-list">

	<span>Сортировка по рейтингу, направление: </span>
	<a href="<?=$APPLICATION->GetCurPageParam("sort=RANK_NEWS_ASC", array("sort"), false);?>">по возрастанию</a>
    <a href="<?=$APPLICATION->GetCurPageParam("sort=RANK_NEWS_DESC", array("sort"), false);?>">по убыванию</a><br>

<?foreach($arResult["ITEMS"] as $arItem):?>

	<?$APPLICATION->IncludeComponent("bitrix:rating.vote","",
		Array(
			"ENTITY_TYPE_ID" => "THEMES",
			"ENTITY_ID" => $arItem['ID']
		),
		null,
		array("HIDE_ICONS" => "Y")
	);?>

	<p class="news-item">
	<?$arItem["RANK_NEWS2"] = CRatings::GetRatingVoteResult("THEMES", $arItem['ID']);
	$arItem["RANK_NEWS2"] = $arItem["RANK_NEWS2"]["TOTAL_POSITIVE_VOTES"];
	CIBlockElement::SetPropertyValuesEx($arItem["ID"], false, array("RANK_NEWS2" => $arItem["RANK_NEWS2"]));
	?>
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
						class="preview_picture"
						border="0"
						src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
						width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
						height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
						alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
						title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
						style="float:left"
						/></a>
		<?endif?>
		<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
		<?endif?>
		<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
				<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
		<?endif;?>
		<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<?echo $arItem["PREVIEW_TEXT"];?>
		<?endif;?>
		<?/*if(isset($arParams["RANK_NEWS2"])):*/?>
			<b><?
				echo "ИТОГО рейтинг: " . $arItem["RANK_NEWS2"];
				?></b><br />

		<?/*endif;*/?>
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<div style="clear:both"></div>
		<?endif?>
		<?foreach($arItem["FIELDS"] as $code=>$value):?>
			<small>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			</small><br />
		<?endforeach;?>
		<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
			<small>
			<?=$arProperty["NAME"]?>:&nbsp;
			<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?endif?>
			</small><br />
		<?endforeach;?>
	</p>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>



