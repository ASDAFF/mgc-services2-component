<?php
if (($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'top')
	|| ($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'bottom')
	|| (empty($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'])))
{
	$direction = 'horizontal';
}
elseif(($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'left')
	|| ($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'right'))
{
	$direction = 'vertical';
}



if ($direction)
{
	if ($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'right')
	{
		?><div style="float: right"><?php
	}

	$APPLICATION->IncludeComponent("mgc:photogallery", ".default", array(
			"IBLOCK_TYPE" => "news",
			"IBLOCK_ID" => $arParams["IBLOCK_GALLERY_ID"],
			"BEHAVIOUR" => "SIMPLE",
			"SECTION_ID" => $arResult["GALLERY_SECTION_ID"],
			"ELEMENT_LAST_TYPE" => "none",
			"ELEMENT_SORT_FIELD" => "SORT",
			"ELEMENT_SORT_ORDER" => "asc",
			"ELEMENT_SORT_FIELD1" => "",
			"ELEMENT_SORT_ORDER1" => "asc",
			"PROPERTY_CODE" => array(
				0 => "",
				1 => "",
			),
			"USE_DESC_PAGE" => "Y",
			"PAGE_ELEMENTS" => "1",
			"PAGE_ELEMENTS_ADD_PHOTO" => "N",
			"DETAIL_URL" => "detail.php?SECTION_ID=#SECTION_ID#&ELEMENT_ID=#ELEMENT_ID#",
			"DETAIL_SLIDE_SHOW_URL" => "slide_show.php?SECTION_ID=#SECTION_ID#&ELEMENT_ID=#ELEMENT_ID#",
			"SEARCH_URL" => "search.php",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"SET_TITLE" => "Y",
			"USE_PERMISSIONS" => "N",
			"GROUP_PERMISSIONS" => array(
			),
			"DATE_TIME_FORMAT" => "d.m.Y",
			"SET_STATUS_404" => "N",
			"PATH_TO_USER" => "/company/personal/user/#USER_ID#",
			"NAME_TEMPLATE" => "",
			"SHOW_LOGIN" => "Y",
			"CAROUSEL_USE" => "Y",
			"CAROUSEL_DIRECTION" => $direction,
			"CAROUSEL_CIRCULAR" => "N",
			"CAROUSEL_PHOTO_COUNT" => "8",
			"CAROUSEL_LINE_COUNT" => "1",
			"CAROUSEL_AUTOSCROLL_DELAY" => "0",
			"PREVIEW_STYLE_USE" => "Y",
			"PREVIEW_COLOR" => "normal",
			"PREVIEW_BLUR" => "0",
			"PREVIEW_BRIGHTNESS" => "100",
			"PREVIEW_BRIGHTNESS_HOVER" => "100",
			"ADDITIONAL_SIGHTS" => array(
			),
			"PICTURES_SIGHT" => "",
			"THUMBNAIL_SIZE" => "94",
			"SHOW_PAGE_NAVIGATION" => "bottom",
			"SHOW_RATING" => "N",
			"SHOW_SHOWS" => "N",
			"SHOW_COMMENTS" => "N",
			"MAX_VOTE" => "5",
			"VOTE_NAMES" => array(
				0 => "1",
				1 => "2",
				2 => "3",
				3 => "4",
				4 => "5",
				5 => "",
			),
			"DISPLAY_AS_RATING" => "rating",
			"RATING_MAIN_TYPE" => ""
		),
		false
	);

	if ($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'right')
	{
		?></div><?php
	}
}