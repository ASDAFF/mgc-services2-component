<?
$arElement = $arResult['ELEMENT'];

$APPLICATION->IncludeComponent("mgc:pages2", ".default", array(
	"HEADER" => $arElement["NAME"],
	"HEADER_FONT_SIZE" => (int)$arElement["PROPERTY_HEADER_FONT_SIZE"]['VALUE_XML_ID'],
	"QUOTE" => $arElement["PROPERTY_QUOTE"]['VALUE'],
	"QUOTE_FONT_SIZE" => (int)$arElement["PROPERTY_QUOTE_FONT_SIZE"]['VALUE_XML_ID'],
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "8640000"
	),
	false
);

?>
	<div style="text-align: center"><?php

		$subheadersFontSize = false;
		$pramSize = (int)$arElement['PROPERTY_SUBHEADERS_FONT_SIZE']['VALUE_XML_ID'];
		if (!empty($pramSize))
			$subheadersFontSize = ' style=" font-size: ' . $pramSize . 'pt !important;" ';


	if (!empty($arResult["headers"]) && $arResult['SUBHEADERS_TOP'] == 'Y')
	{
		foreach ($arResult["headers"] as $subheader)
		{
			if (is_array($subheader))
			{
				$string = '<a href="' . $subheader['href'] . '">' . $subheader['value'] . '</a>';
			}
			else
			{
				$string = $subheader['value'];
			}
			?><span class="main-subheader" <?=$subheadersFontSize?$subheadersFontSize:''?>><?=$string?></span><?
		}
	}
	?>
	</div>
<?
if ($arElement['PROPERTY_GALLERY_ENABLED']['VALUE_XML_ID'] && (empty($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID']) || ($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'top')))
{
	?><div style="clear: both;  padding-top: 10px;"></div><?php

		include('gallery.php');

	?><div style="clear: both; padding-bottom: 10px;"></div><?php
}
?>
<div class="row-fluid">
<?

if ($arElement['PROPERTY_GALLERY_ENABLED']['VALUE_XML_ID'] && ($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'left'))
{
	?><div class="span2"><?php
		include('gallery.php');
	?></div><?php
}

if (($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'left')
	|| ($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'right'))
{
	$span = 'span10';
}
else
{
	$span = 'span12';
}

?><div class="<?=$span?>"><?php

$APPLICATION->IncludeComponent("bitrix:news.detail", "services", array(
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"ELEMENT_ID" => $arElement["ID"],
		"ELEMENT_CODE" => "",//$arElement["CODE"],
		"CHECK_DATES" => "Y",
		"IBLOCK_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"USE_PERMISSIONS" => "N",
		"PAGER_TEMPLATE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Страница",
		"PAGER_SHOW_ALL" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "N",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);
?>
</div>
<?php

if ($arElement['PROPERTY_GALLERY_ENABLED']['VALUE_XML_ID'] && ($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'right'))
	{
	?><div class="span2"><?php

		include('gallery.php');

		?></div><?php
	}
?>

	</div>
<?php

if ($arElement['PROPERTY_GALLERY_ENABLED']['VALUE_XML_ID'] && ($arElement['PROPERTY_PHOTOGAL_POSITION']['VALUE_XML_ID'] == 'bottom'))
{

	?><div style="clear: both;  padding-top: 10px;"></div><?php

		include('gallery.php');

	?><div style="clear: both; padding-bottom: 10px;"></div><?php
}
?>
<div style="text-align: center"><?php
	if (!empty($arResult["headers"])  && $arResult['SUBHEADERS_BOTTOM'] == 'Y')
	{
		foreach ($arResult["headers"] as $subheader)
		{
			if (is_array($subheader))
			{
				$string = '<a href="' . $subheader['href'] . '">' . $subheader['value'] . '</a>';
			}
			else
			{
				$string = $subheader['value'];
			}
			?><span class="main-subheader" <?=$subheadersFontSize?$subheadersFontSize:''?>><?=$string?></span><?
		}
	}
	?>
</div>
<div style="clear: both;"></div>
<div class="service-element-contacts">
	<div class="service-element-order"><a href="" class="service-element-order-a" onclick="recall_show('zakazat', '<?=$arResult['MAIN_CODE'];?>', '<?=$arResult['CODE'];?>'); return false;">заказать</a></div>

	<div class="social-networks">
		<?$APPLICATION->IncludeComponent("denisoft:share.yandex", ".default", array(
				"YA_LANG" => "ru",
				"YA_BVAR" => "icon",
				"YA_BUTTONS" => array(
					0 => "yaru",
					1 => "vkontakte",
					2 => "facebook",
					3 => "twitter",
					4 => "odnoklassniki",
					5 => "moimir",
					/*6 => "lj",
					7 => "friendfeed",
					8 => "moikrug",*/
				)
			),
			false
		);?>
	</div>
<?php $GLOBALS['DISABLE_SOCIAL'] = false ; ?>
	<div class="service-element-phones"></div>
	<script>
		var msk_phone = $('#phone-num-msk').text();
		var spb_phone = $('#phone-num-spb').text();

		if (msk_phone.length)
			$('div.service-element-phones').text($('div.service-element-phones').text() + ' ' + msk_phone + "\n");

		if (spb_phone.length)
			$('div.service-element-phones').text($('div.service-element-phones').text() + ' ' + spb_phone);

	</script>
</div>
<div style="margin-top: 50px;"><?php
	global $USER;
	if ($USER->IsAdmin()) { ?>
		<h3>Загрузить фото</h3>
		  <?$APPLICATION->IncludeComponent(
			"bitrix:photogallery.upload",
			".default",
			Array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_GALLERY_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_GALLERY_ID"],
				"SECTION_ID" => $arResult["GALLERY_SECTION_ID"],
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
		);?> <?php }
	?></div>