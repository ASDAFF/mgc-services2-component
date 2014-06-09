<?php
if ($arResult['type'] == 'element')
{
	require_once('element.php');
}
else
{
	require_once('all.php');
}