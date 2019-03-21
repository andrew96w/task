<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");
use Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock;

if(!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

if($_GET["sort"]=="RANK_NEWS_ASC") {
    $arParams["SORT_ORDER1"]="ASC";
}
if($_GET["sort"]=="RANK_NEWS_DESC") {
    $arParams["SORT_ORDER1"]="DESC";
}
$sortVar = 0;
if($_GET["sort"]=="RANK_NONE") {
    $sortVar = 0;
}
if($_GET["sort"]=="RANK_THREE") {
    $sortVar = 3;
}
if($_GET["sort"]=="RANK_FIVE") {
    $sortVar = 5;
}
if($_GET["sort"]=="RANK_TEN") {
    $sortVar = 10;
}

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if(strlen($arParams["IBLOCK_TYPE"]) <= 0)
    $arParams["IBLOCK_TYPE"] = "news";
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

$arParams["SORT_BY1"] = trim($arParams["SORT_BY1"]);
if(strlen($arParams["SORT_BY1"]) <= 0)
    $arParams["SORT_BY1"] = "ACTIVE_FROM";

$arSelect = array_merge($arParams["FIELD_CODE"], array(
    "ID",
    "IBLOCK_ID",
    "IBLOCK_SECTION_ID",
    "NAME",
    "ACTIVE_FROM",
    "TIMESTAMP_X",
    "DETAIL_PAGE_URL",
    "LIST_PAGE_URL",
    "DETAIL_TEXT",
    "DETAIL_TEXT_TYPE",
    "PREVIEW_TEXT",
    "PREVIEW_TEXT_TYPE",
    "PREVIEW_PICTURE",
));

$obParser = new CTextParser;
$arrFilter = array();
$arSort = array(
    $arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
);

$arFilter = Array(
    "IBLOCK_TYPE"=>$arParams["IBLOCK_TYPE"],
    "IBLOCK_ID"=>$arParams["IBLOCK_ID"],
    "ACTIVE"=>"Y",
    ">=PROPERTY_RANK_NEWS2"=>$sortVar,
);
$res = CIBlockElement::GetList($arSort, $arFilter, false, array("nPageSize" => $arParams["NEWS_COUNT"]), $arSelect);
$res->SetUrlTemplates($arParams["DETAIL_URL"], "", $arParams["IBLOCK_URL"]);
while($obElement = $res->GetNextElement())
{
    $arItem = $obElement->GetFields();

    if($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
        $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"]);

    Iblock\Component\Tools::getFieldImageData(
        $arItem,
        array('PREVIEW_PICTURE', 'DETAIL_PICTURE'),
        Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
        'IPROPERTY_VALUES'
    );
    $navComponentParameters = array();

    $arResult["ITEMS"][] = $arItem;
    $arResult["SORT_VAR"] = $sortVar;
    $arResult["ELEMENTS"][] = $arItem["ID"];
    $arResult["NAV_STRING"] = $res->GetPageNavStringEx(
        $navComponentObject,
        $arParams["PAGER_TITLE"],
        $arParams["PAGER_TEMPLATE"],
        $arParams["PAGER_SHOW_ALWAYS"],
        $this,
        $navComponentParameters
    );
}

$this->includeComponentTemplate();