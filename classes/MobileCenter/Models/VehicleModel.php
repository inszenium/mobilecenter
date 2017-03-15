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
 * Class VehicleList
 *
 * List all Vehicles
 *
 * @copyright  2017 inszenium
 * @author     Kirsten Roschanski <kirsten@kat-webdesign.de>
 */
class VehicleModel extends \Model
{
  /**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_vehicle';
  
  
  /**
	 * Find all published FAQs by their parent ID
	 *
	 * @param int   $intPid     The parent ID
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\FaqModel|null A collection of models or null if there are no FAQs
	 */
	public static function findPublished($intLimit=0, $intOffset=0)
	{    
		$t = static::$strTable;
    

    $strQuery = "SELECT $t.*
              FROM $t 
              WHERE 
                published=1";
    foreach ($GLOBALS['TL_DCA']['tl_vehicle']['fields'] as $id => $arrConfig) {
      if(\Input::get($id)) {       
        switch ($id) {
            case 'vehicle_typenumber':
              if(\Input::get('vehicle_typenumber') == 'G') {
                $strQuery .= ' AND ( vehicle_typenumber = ? OR vehicle_typenumber = ? )';
                $arrExecute[] = 'G';
                $arrExecute[] = 'D';
                
              } else {
                $strQuery .= " AND {$id} = ?";
                $arrExecute[] = \Input::get($id); 
              }    
              break;
            case 'milage':
            case 'price':
              $strQuery .= " AND {$id} <= " . mysql_real_escape_string(\Input::get($id));
              break;
            case 'fuel':
              $strQuery .= " AND (";
              
              foreach (\Input::get($id) as $f => $fuel) {
                $strQuery .= $f != 0 ? ' OR' : '';
                $strQuery .= " {$id} = ?";
                $arrExecute[] = $fuel;  
              }
              $strQuery .= ") ";
              break;
            default:
              $strQuery .= " AND {$id} = ?";
              $arrExecute[] = \Input::get($id);  
        }
      }
    }            
    
    $strQuery .= " ORDER BY $t.sorting " .
              ( $intLimit > 0 ? "LIMIT $intLimit " : '' ) . 
              ( $intOffset > 0 ? "OFFSET $intOffset " : '' );

   
		$objStatement = \Database::getInstance()->prepare($strQuery);

		$objStatement = static::preFind($objStatement);
    
		$objResult = $objStatement->execute($arrExecute);
  
		if ($objResult->numRows < 1) {
			return null;
		}

		$objResult = static::postFind($objResult);

    return $objResult;
	}
  
  
  /**
	 * Find published news items by their parent ID and ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of parent IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The NewsModel or null if there are no news
	 */
	public static function findPublishedByIdOrAlias($varId, array $arrOptions=array())
	{
    $time = time();
		$t = static::$strTable;
    
		$strQuery = "SELECT $t.*
                  FROM $t
                  WHERE 
                    ($t.id=? OR $t.alias=?)" . (count($arrOptions) > 0 ? ' AND ' . implode(" AND ", $arrOptions) : '');
      

		$objStatement = \Database::getInstance()->prepare($strQuery);

		$objStatement = static::preFind($objStatement);
		$objResult = $objStatement->executeUncached((is_numeric($varId) ? $varId : 0), $varId);

		if ($objResult->numRows < 1) {
			return null;
		}

		$objResult = static::postFind($objResult);

    return $objResult->row();
  }  
  
}
