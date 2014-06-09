<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME"			=> GetMessage("MGC_SERVICES_NAME"),
	"DESCRIPTION"	=> GetMessage("MGC_SERVICES_DESCRIPTION"),
	"ICON"			=> "/images/icon.gif",
	"SORT"			=> 100,
	"CACHE_PATH"	=> "Y",
	"PATH"			=> array(
		"ID"	=> "mgc_components",
		"NAME"	=> GetMessage("MGC_SERVICES_GLOBAL_DIR_NAME"),
	),
);

?>