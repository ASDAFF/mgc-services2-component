<div class="s-double-line"></div>
<?php
$num = 1;
$hrefBase = substr($_SERVER['REAL_FILE_PATH'], 0, strrpos($_SERVER['REAL_FILE_PATH'], '/') + 1);

foreach ($arResult as $iSectionId => $arItem)
{

	$aMainElement = $arItem['MAIN_ELEMENT'];
	/**
	 * Формируем заголовок
	 */
	if (!empty($aMainElement['CODE']))
	{
		$sMainHeader = '<a href="' . $aMainElement['CODE'] . '">' . $aMainElement['NAME'] . '</a>';
	}
	elseif (!empty($aMainElement['NAME']))
	{
		$sMainHeader = $aMainElement['NAME'];
	}
	else
	{
		$sMainHeader = $arItem['SECTION']['NAME'];
	}
	/**
	 * Preview text
	 */
	$sPreviewText = !empty($aMainElement['PREVIEW_TEXT'])
		? $aMainElement['PREVIEW_TEXT']
		: '';

	$sPreviewText .= !empty($aMainElement['CODE'])
		?   '<br/><a href="' .$hrefBase . $aMainElement['CODE'] . '">подробнее</a>'
		:   '';

	?><div class="row-fluid"><?php
	if ($num%2 == 0) {
		?><div class="span2" id="<?=$arItem['SECTION']['CODE']?>"></div><?php
	}
	?>
	<div class="span10 s-border-bottom-dotted">
	<div class="row-fluid">
		<div class="span4 service-number" style="background: url(<?=$arItem['SECTION']['PICTURE']['SRC']?>) center 35px no-repeat">
			<div><?=$num?></div>
			<p style="font-family: Bodoni72C; font-size: 22px;"><a href="" onclick="recall_show('zakazat', '<?=$arItem['SECTION']['CODE']?$arItem['SECTION']['CODE']:$arItem['MAIN_ELEMENT']['CODE'];?>', ''); return false;">заказать</a></p>
		</div>
		<div class="span6 s-min-height-260">
			<div class="service-header"><?=$sMainHeader;?></div><?
			foreach ($arItem['ELEMENTS'] as $aSubElement)
			{
				echo '<div class="service-subheader">';
				echo !empty($aSubElement['CODE']) && !empty($aMainElement['CODE'])
					?   '<a href="'. $hrefBase . $aMainElement['CODE'] . '/' . $aSubElement['CODE'] . '">' . $aSubElement['NAME'] . '</a>'
					:   $aSubElement['NAME'];
				echo '</div>';
			}
			?>
			<div style="clear: both;"></div>
			<div class="service-message"><?=$sPreviewText?></div>
		</div>
		<div class="span2 s-min-height-260"><?

			if ($arItem["SECTION"]["UF_PHOTOGAL_ENABLED"])
			{
			$APPLICATION->IncludeComponent("mgc:photogallery", ".default", array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_GALLERY_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_GALLERY_ID"],
					"BEHAVIOUR" => "SIMPLE",
					"SECTION_ID" => $arItem["SECTION"]["UF_GAL_SECT_ID"],
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
					"CAROUSEL_DIRECTION" => "vertical",
					"CAROUSEL_CIRCULAR" => "N",
					"CAROUSEL_PHOTO_COUNT" => "3",
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
					"THUMBNAIL_SIZE" => "100",
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
			}?></div>
	</div>
	</div><?php
	if ($num%2 == 1) {
		?><div class="span2" id="<?=$arItem['SECTION']['CODE']?>"></div><?php
	}
	?></div><?php
	$num ++;
}
?><div><?php
	global $USER;
	if ($USER->IsAdmin()) { ?>
		<h3>Загрузить фото</h3>
		  <?$APPLICATION->IncludeComponent(
		"bitrix:photogallery.upload",
		".default",
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_GALLERY_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_GALLERY_ID"],
			"SECTION_ID" => "",
			"INDEX_URL" => "index.php",
			"SECTION_URL" => "section.php?SECTION_ID=#SECTION_ID#",
			"SET_TITLE" => "Y",
			"UPLOADER_TYPE" => "flash",
			"UPLOAD_MAX_FILE_SIZE" => "15",
			"MODERATION" => "N",
			"ALBUM_PHOTO_THUMBS_WIDTH" => "120",
			"USE_WATERMARK" => "N",
			"THUMBNAIL_SIZE" => "120",
			"JPEG_QUALITY1" => "100",
			"ORIGINAL_SIZE" => "1280",
			"P_SHOW_RESIZER" => "Y",
			"JPEG_QUALITY" => "100"
		)
	);?> <?php } ?></div>