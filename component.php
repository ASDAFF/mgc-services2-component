<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if ($this->StartResultCache(false))
{
	if (!CModule::IncludeModule('iblock'))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	// Движок
	$oServicesMgc = new ServicesMgc($arParams);

	/**
	 * Смотрим, пришел ли элемент
	 */
	$arElement = $oServicesMgc->getElement();

	if ($arElement)
	{
		/**
		 * Получаем все элементы
		 */
		$arElements     = $oServicesMgc->getAllElements();
		// получаем главный элемент
		$MainElement    = $arElements[$arElement['IBLOCK_SECTION_ID']]['MAIN_ELEMENT'];


		/**
		 * Отображаем или нет подзаголовки
		 */
		$arSection = $oServicesMgc->getSectionsByIBlockId($arElement['IBLOCK_SECTION_ID']);
		$arResult['SUBHEADERS_TOP'] = $arElement['PROPERTY_SUBHEADERS_TOP']['VALUE_XML_ID']? 'Y': 'N';
		if (empty($arSection['UF_SUBHEADER_TOP']))
		{
			$arResult['SUBHEADERS_TOP'] = 'N';
		}

		$arResult['SUBHEADERS_BOTTOM'] = $arElement['PROPERTY_SUBHEADERS_BOTTOM']['VALUE_XML_ID']? 'Y': 'N';
		if (empty($arSection['UF_SUBHEADER_BOTTOM']))
		{
			$arResult['SUBHEADERS_BOTTOM'] = 'N';
		}


		/**
		 * Для корректной работы "Заказать"
		 */
		if ($MainElement['ID'] == $arElement['ID'])
		{
			$arElement['main']       = true;
			$arElement['MAIN_CODE']  = $arElement['CODE'];
			$arElement['CODE'] = '';
		}
		else
		{
			$arElement['main']       = false;
			$arElement['MAIN_CODE']  = $MainElement['CODE'];
		}

		/**
		 * Заголовки для отображения
		 */
		$arResult['headers'] = $oServicesMgc->getElementsHeaders($arElement);

		/**
		 * Секция фоточек
		 */
		if (!empty($arElement['PROPERTY_PHOTO_SECTION']['VALUE']))
		{
			$arResult["GALLERY_SECTION_ID"] = $arElement['PROPERTY_PHOTO_SECTION']['VALUE'];
		}
		else
		{
			$arResult["GALLERY_SECTION_ID"] = $arElements[$arElement['IBLOCK_SECTION_ID']]['SECTION']['UF_GAL_SECT_ID'];
		}

		$arResult['ELEMENT'] = $arElement;
		$arResult['type']   = 'element';
	}
	else
	{
		$arResult = $oServicesMgc->getAllElements($arParams);
	}

	$this->IncludeComponentTemplate();
}

class ServicesMgc
{

	private $arParams = array();

	public function __construct($arParams)
	{
		$this->arParams = $arParams;
	}
	/**
	 * получает пришедший элемент, если есть
	 * @return array|bool
	 */
	public function getElement()
	{
		if (isset($_REQUEST['element']))
		{
			if (false !== strrpos($_REQUEST['element'], '/'))
			{
				$elementName = substr($_REQUEST['element'], strrpos($_REQUEST['element'], '/') + 1);
			}
			else
			{
				$elementName = $_REQUEST['element'];
			}

			if (strlen($elementName))
			{
				$element = $this->getElementsIblockIdSectionIdCode(null, $elementName);
				return $element[0];
			}
			return false;
		}
		return false;
	}


	/**
	 * Получает элемент или массив элементов по ид инфоблока и, возможно, ид секции и коду
	 * @param null $iSectionId
	 * @param null $sCode
	 * @return array
	 */
	private function getElementsIblockIdSectionIdCode($iSectionId = null, $sCode = null)
	{
		$arSort     = array('SORT' => 'ASC', 'ID' => 'DESC');
		$arFilter   = array('IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'ACTIVE' => 'Y');

		if (!empty($iSectionId)) $arFilter['SECTION_ID'] = $iSectionId;

		if (!empty($sCode)) $arFilter['CODE'] = $sCode;

		$arSelect   = array('ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT','DETAIL_PICTURE', 'DETAIL_TEXT', 'PROPERTY_*');
		$rsElements = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);

		$arElements = array();

		while ($oElement = $rsElements->GetNextElement())
		{
			$arElement = $oElement->GetFields();
			$arElement['DETAIL_PICTURE'] = CFIle::GetFileArray($arElement['DETAIL_PICTURE']);
			$arElement['PREVIEW_PICTURE'] = CFIle::GetFileArray($arElement['PREVIEW_PICTURE']);

			$arProperties = $oElement->GetProperties();

			foreach ($arProperties as $sPropertyCode => $aPropertyValue)
			{
				$arElement['PROPERTY_' . $sPropertyCode] = $aPropertyValue;//['VALUE_XML_ID'];
			}
			$arElements[] = $arElement;
		}

		//if (count($arElements) == 1) $arElements = array_shift($arElements);

		return $arElements;
	}


	/**
	 * Получаем список всех элементов, рассортированных по категориям. Отдельно еще раз дублируем главный элемент (если есть)
	 * @return array
	 */
	public function getAllElements()
	{
		$arResult = array();

		$arSections = $this->getSectionsByIBlockId();

		foreach ($arSections as $iSectionId => $aSection)
		{

			/**
			 * Разделяем элементы на главные и на второстепенные
			 */
			$aDividedElements = $this->getDividedElementsByIBlockIdSection($aSection);

			$arResult[$iSectionId]['MAIN_ELEMENT']  = $aDividedElements['MAIN_ELEMENT'];
			$arResult[$iSectionId]['ELEMENTS']      = $aDividedElements['ELEMENTS'];
			$arResult[$iSectionId]['SECTION']       = $aSection;
		}
		return $arResult;
	}

	/**
	 * Возвращает разделенные элементы по ид инфоблока и секции
	 * @param $aSection
	 * @return array
	 */
	public function getDividedElementsByIBlockIdSection($aSection)
	{
		$arElements  = $this->getElementsIblockIdSectionIdCode($aSection['ID']);

		return $this->divideElements($arElements, $aSection['UF_MAIN_ID']);
	}

	/**
	 * Получаем все категории инфоблока (либо одну)
	 * @param null $iSectionId
	 * @return array
	 */
	public function getSectionsByIBlockId($iSectionId = null)
	{
		$arResult = array();

		$arSort   = array('SORT' => 'ASC', 'ID' => 'DESC');
		$arFilter = Array('IBLOCK_ID'=>$this->arParams['IBLOCK_ID'], 'GLOBAL_ACTIVE'=>'Y', 'ACTIVE'=> 'Y');

		if (!empty($iSectionId)) $arFilter['ID'] = (int)$iSectionId;

		$arSelect = array('ID', 'CODE', 'IBLOCK_ID', 'SORT', 'NAME', 'PICTURE', 'DESCRIPTION', 'DETAIL_PICTURE', 'UF_*');

		$db_list = CIBlockSection::GetList($arSort, $arFilter, true, $arSelect);

		while ($ar_result = $db_list->GetNext())
		{
			$ar_result['PICTURE']           = CFIle::GetFileArray($ar_result['PICTURE']);
			$ar_result['DETAIL_PICTURE']    = CFIle::GetFileArray($ar_result['DETAIL_PICTURE']);

			$arResult[$ar_result['ID']] = $ar_result;
		}

		if (!empty($iSectionId)) $arResult = array_shift($arResult);//[$arResult['ID']];

		return $arResult;
	}

	/**
	 * Разделяет элементы на главный и остальные
	 * @param $arElements
	 * @param $sectionMainElementId
	 * @return array
	 */
	public function divideElements($arElements, $sectionMainElementId)
	{
		$iMainElementId = (int)$sectionMainElementId;
		$aMainElement = array();

		if ($iMainElementId)
		{
			foreach ($arElements as $key => $aElement)
			{
				if ($iMainElementId == $aElement['ID'])
				{
					$aMainElement = $aElement;
					unset($arElements[$key]);
					break;
				}
			}
		}
		return array('MAIN_ELEMENT' => $aMainElement, 'ELEMENTS' => $arElements);
	}

	/**
	 * Возвращает подзаголовки на остальные элементы категории
	 * @param $aShowedElement
	 * @return array
	 */
	public function getElementsHeaders($aShowedElement)
	{
		$iSectionId = $aShowedElement['IBLOCK_SECTION_ID'];
		/**
		 * Получаем секцию элемента
		 */
		$arSection = $this->getSectionsByIBlockId($iSectionId);

		/**
		 * Получаем все элементы секции
		 */
		$arElements         = $this->getElementsIblockIdSectionIdCode($iSectionId);
		$arSortedElements   = $this->divideElements($arElements, $arSection['UF_MAIN_ID']);
		$MainElementCode    = $arSortedElements['MAIN_ELEMENT']['CODE'];
		/**
		 * Основа для ссылки
		 */
		$hrefBase = substr($_SERVER['REAL_FILE_PATH'], 0, strrpos($_SERVER['REAL_FILE_PATH'], '/') + 1);

		$aHeaders = array();
		foreach ($arElements as $aElement)
		{
			if ($aShowedElement['ID'] == $aElement['ID']) continue;

			if (!empty($aElement['CODE']))
			{
				if ($aShowedElement['main'])
				{
					$aHeaders[] = array('value' => $aElement['NAME'], 'href' => $hrefBase . $aShowedElement['~CODE'] . '/' . $aElement['CODE']);
				}
				else
				{
					$aHeader = array('value' => $aElement['NAME'], 'href' => $hrefBase . $MainElementCode);
					if ($MainElementCode !=  $aElement['CODE'])
					{
						$aHeader['href'] .= '/' . $aElement['CODE'];
					}
					$aHeaders[] = $aHeader;
				}
			}
			else
				$aHeaders[] = $aElement['NAME'];
		}

		return $aHeaders;
	}
}