<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock")) return;

$arTypesEx_IBLOCK_TYPE = array("-" => " ");
$rsIBlockTypes_IBLOCK_TYPE = CIBlockType::GetList(array("SORT" => "ASC"));
while($arIBlockTypes_IBLOCK_TYPE = $rsIBlockTypes_IBLOCK_TYPE->Fetch())
	if ($arIBType_IBLOCK_TYPE = CIBlockType::GetByIDLang($arIBlockTypes_IBLOCK_TYPE["ID"], LANG))
		$arTypesEx_IBLOCK_TYPE[$arIBlockTypes_IBLOCK_TYPE["ID"]] = $arIBType_IBLOCK_TYPE["NAME"];

$arIBlocks_IBLOCK_ID = array();
$arFilter = array("SITE_ID" => $_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"] != "-" ? $arCurrentValues["IBLOCK_TYPE"] : ""));
$rsIBlock_IBLOCK_ID = CIBlock::GetList(array("SORT" =>" ASC"), $arFilter);
while($arIBlock_IBLOCK_ID = $rsIBlock_IBLOCK_ID->Fetch())
	$arIBlocks_IBLOCK_ID[$arIBlock_IBLOCK_ID["ID"]] = $arIBlock_IBLOCK_ID["NAME"];

$arTypesEx_IBLOCK_TYPE_G = array("-" => " ");
$rsIBlockTypes_IBLOCK_TYPE_G = CIBlockType::GetList(array("SORT" => "ASC"));
while($arIBlockTypes_IBLOCK_TYPE_G = $rsIBlockTypes_IBLOCK_TYPE_G->Fetch())
	if ($arIBType_IBLOCK_TYPE_G = CIBlockType::GetByIDLang($arIBlockTypes_IBLOCK_TYPE_G["ID"], LANG))
		$arTypesEx_IBLOCK_TYPE_G[$arIBlockTypes_IBLOCK_TYPE_G["ID"]] = $arIBType_IBLOCK_TYPE_G["NAME"];

$arIBlocks_IBLOCK_ID_G = array();
$arFilter_G = array("SITE_ID" => $_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_GALLERY_TYPE"] != "-" ? $arCurrentValues["IBLOCK_GALLERY_TYPE"] : ""));
$rsIBlock_IBLOCK_ID_G = CIBlock::GetList(array("SORT" =>" ASC"), $arFilter_G);
while($arIBlock_IBLOCK_ID_G = $rsIBlock_IBLOCK_ID_G->Fetch())
	$arIBlocks_IBLOCK_ID_G[$arIBlock_IBLOCK_ID_G["ID"]] = $arIBlock_IBLOCK_ID_G["NAME"];


$arComponentParameters = array(
	"GROUPS" => array(
		"DATA_SORT" => array(
			"NAME" => GetMessage("MGC_SERVICES_PARAM_SORT_BLOCK"),
		),
	),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("MGC_SERVICES_PARAM_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx_IBLOCK_TYPE,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("MGC_SERVICES_PARAM_IBLOCK_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks_IBLOCK_ID,
			//"REFRESH" => "Y",
		),
		"IBLOCK_GALLERY_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("MGC_SERVICES_PARAM_IBLOCK_GALLERY_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx_IBLOCK_TYPE_G,
			"REFRESH" => "Y",
		),
		"IBLOCK_GALLERY_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("MGC_SERVICES_PARAM_IBLOCK_GALLERY_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks_IBLOCK_ID_G,
			//"REFRESH" => "Y",
		),
		"SUBHEADERS_TOP" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("MGC_SERVICES_PARAM_SUBHEADERS_TOP"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'Y',
		),
		"SUBHEADERS_BOTTOM" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("MGC_SERVICES_PARAM_SUBHEADERS_BOTTOM"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'Y',
		),
		'CACHE_TIME' => array('DEFAULT' => 8640000)
	),
);