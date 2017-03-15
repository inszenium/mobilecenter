<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 inszenium
 *
 * @package   MobileCenter
 * @author    Kirsten Roschanski <kirsten@kirsten-roschanski.de>
 * @license   LGPL
 * @copyright 2017
 */

namespace inszenium\MobileCenter;

/**
 * Class VehicleModule
 *
 * List all Vehicles
 *
 * @copyright  2017 inszenium
 * @author     Kirsten Roschanski <kirsten@kirsten-roschanski.de>
 */
abstract class VehicleModule extends \Module
{
  /**
	 * URL cache array
	 * @var array
	 */
	private static $arrUrlCache = array();
  
	/**
	 * Parse one or more items and return them as array
	 * @param array
	 * @param boolean
	 * @return array
	 */
	protected function parseVehicles($arrVehicles)
	{
		$count = 0;
    $limit = count($arrVehicles);

		if ($limit < 1) {
			return '';
		}
    
		foreach($arrVehicles as $arrVehicle)	{          
      $return .= $this->parseVehicle($arrVehicle, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'), $count);
		} 
    
		return $return;
	}  
  
	/**
	 * Parse an item and return it as string
	 * @param object
	 * @param boolean
	 * @param string
	 * @param integer
	 * @return string
	 */
	protected function parseVehicle($arrVehicle, $strClass='', $intCount=0)
	{
		global $objPage;
    
		$objTemplate = new \FrontendTemplate('ce_vehicle');
		$objTemplate->setData($arrVehicle);

		$objTemplate->class = $strClass;
		$objTemplate->titel = $arrVehicle['headline'];
		$objTemplate->linkHeadline = $this->generateLink($arrVehicle, $arrVehicle['titel']);
		$objTemplate->details = $this->generateLink($arrVehicle);
 
		return $objTemplate->parse();
	}  
  
  /**
	 * Generate a URL and return it as string
	 * @param array
	 * @param boolean
	 * @return string
	 */
	protected function generateLink($arrVehicle, $title='')
	{
		$strCacheKey = 'id_' . $arrVehicle['id'];

		// Load the URL from cache
		if (isset(self::$arrUrlCache[$strCacheKey]))
		{
			return self::$arrUrlCache[$strCacheKey];
		}

		// Initialize the cache
		self::$arrUrlCache[$strCacheKey] = null;
    
    if($this->jumpTo) {
      $objPage = \PageModel::findPublishedById($this->jumpTo);       
    } else {
      return '';  
    }
    
    if($objPage !== null) {
      self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row(), '/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $arrVehicle['alias'] != '') ? $arrVehicle['alias'] : $arrVehicle['id'])));
    }

		return self::$arrUrlCache[$strCacheKey];
	}  
}
