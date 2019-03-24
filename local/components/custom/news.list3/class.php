<?

use Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Main\Localization;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class NewsCustomComponent extends CBitrixComponent
{
    public $res;
    public $navComponentObject;
    public $arComponentParameters;
    protected function checkRequiredModules()
    {
        if (!Loader::includeModule('iblock')) {
            throw new Main\SystemException(Localization\Loc::getMessage("MODULE_NOT_INSTALLED"));
        }
    }

    private function onPrepareComponentParams2()
    {
        if (!isset($this->arParams["CACHE_TIME"]))
            $this->arParams["CACHE_TIME"] = 36000000;

        if ($_GET["sort"]=="RANK_NEWS_ASC") {
            $this->arParams["SORT_ORDER1"]="ASC";
        }
        if ($_GET["sort"]=="RANK_NEWS_DESC") {
            $this->arParams["SORT_ORDER1"]="DESC";
        }
        $this->arParams["SORT_VAR"] = 0;
        if ($_GET["sort"]=="RANK_NONE") {
            $this->arParams["SORT_VAR"] = 0;
        }
        if ($_GET["sort"]=="RANK_THREE") {
            $this->arParams["SORT_VAR"] = 3;
        }
        if ($_GET["sort"]=="RANK_FIVE") {
            $this->arParams["SORT_VAR"] = 5;
        }
        if ($_GET["sort"]=="RANK_TEN") {
            $this->arParams["SORT_VAR"] = 10;
        }

        $this->arParams["IBLOCK_TYPE"] = trim($this->arParams["IBLOCK_TYPE"]);
        if (strlen($this->arParams["IBLOCK_TYPE"]) <= 0)
            $this->arParams["IBLOCK_TYPE"] = "news";
        $this->arParams["IBLOCK_ID"] = trim($this->arParams["IBLOCK_ID"]);

        $this->arParams["SORT_BY1"] = trim($this->arParams["SORT_BY1"]);
        if (strlen($this->arParams["SORT_BY1"]) <= 0)
            $arParams["SORT_BY1"] = "ACTIVE_FROM";
    }

    private function getElements()
    {
        $arSelect = /*array_merge($this->arParams["FIELD_CODE"],)*/ array(
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
        );
        $arSort = array(
            $this->arParams["SORT_BY1"]=>$this->arParams["SORT_ORDER1"],
        );

        $arFilter = Array(
            "IBLOCK_TYPE"=>"news",
            "IBLOCK_ID"=>"1",
            "ACTIVE"=>"Y",
            ">=PROPERTY_RANK_NEWS2"=>$this->arParams["SORT_VAR"],
        );
        $this->res = CIBlockElement::GetList($arSort, $arFilter, false, array("nPageSize" => 5/*$this->arParams["NEWS_COUNT"]*/), $arSelect);
        $this->res->SetUrlTemplates($this->arParams["DETAIL_URL"], "", $this->arParams["IBLOCK_URL"]);
    }

    private function onPrepareComponentResult()
    {
        $obParser = new CTextParser;
        while($obElement = $this->res->GetNextElement()) {

            $arItem = $obElement->GetFields();

            if($this->arParams["PREVIEW_TRUNCATE_LEN"] > 0)
                $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], $this->arParams["PREVIEW_TRUNCATE_LEN"]);

            Iblock\Component\Tools::getFieldImageData(
                $arItem,
                array('PREVIEW_PICTURE', 'DETAIL_PICTURE'),
                Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
                'IPROPERTY_VALUES'
            );
            $navComponentParameters = array();

            $arResult["ITEMS"][] = $arItem;
            $arResult["SORT_VAR"] = $this->arParams["SORT_VAR"];
            $arResult["ELEMENTS"][] = $arItem["ID"];
            $arResult["NAV_STRING"] = $this->res->GetPageNavStringEx(
                $this->navComponentObject,
                $this->arParams["PAGER_TITLE"],
                $this->arParams["PAGER_TEMPLATE"],
                $this->arParams["PAGER_SHOW_ALWAYS"],
                $this,
                $navComponentParameters
            );
        }
        return $arResult;
    }

    public function executeComponent()
    {
        Loader::includeModule('iblock');
        $this->onPrepareComponentParams2();
        try {
            $this->checkRequiredModules();
            $this->getElements();
            $this->arResult = $this->onPrepareComponentResult();
        }
        catch(Exception $e) {
        }
        $this->includeComponentTemplate();
    }

    public function __construct($component = null)
    {
        parent::__construct($component);

        Localization\Loc::loadMessages(__FILE__);
    }
}

CModule::IncludeModule("iblock");
